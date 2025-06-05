<?php

namespace App\Http\Controllers;

use App\Http\Requests\BorrowRequest;
use App\Models\Buku;
use App\Models\Log_Pinjam_Buku;
use App\Models\Member;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

/**
 * Class BorrowController
 *
 * @package App\Http\Controllers
 */
class BorrowController extends Controller
{
    /**
     * Display a listing of the borrow entries.
     *
     * @param  Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request): \Illuminate\View\View
    {
        $query = \App\Models\Log_Pinjam_Buku::query()->with(['buku', 'member']);
        $user = auth()->user();
        // Only show own borrows for member users
        if ($user && $user->role === 'Member' && $user->member) {
            $query->where('member_id', $user->member->member_id);
        }
        if ($request->filled('search')) {
            $search = (string) $request->input('search', '');
            assert(is_string($search));
            $query->where(function ($q) use ($search) {
                assert($q instanceof \Illuminate\Database\Eloquent\Builder);
                $q->whereHas('buku', function ($qb) use ($search) {
                    assert($qb instanceof \Illuminate\Database\Eloquent\Builder);
                    $qb->where('title', 'like', "%$search%")
                        ->orWhere('author', 'like', "%$search%")
                        ->orWhere('isbn', 'like', "%$search%");
                })
                    ->orWhereHas('member', function ($qm) use ($search) {
                        assert($qm instanceof \Illuminate\Database\Eloquent\Builder);
                        $qm->where('name', 'like', "%$search%")
                            ->orWhere('email', 'like', "%$search%");
                    })
                    ->orWhere('status', 'like', "%$search%");
            });
        }
        if ($request->filled('status')) {
            $status = $request->input('status');
            assert(is_string($status));
            $query->where('status', $status);
        }
        $borrows = $query->orderByDesc('borrow_date')->paginate(10)->appends($request->all());
        $statuses = [
            'pending' => 'Pending',
            'approved' => 'Approved',
            'returned' => 'Returned',
            'overdue' => 'Overdue',
            'rejected' => 'Rejected',
        ];
        return \view('vendor.argon.borrow.index', compact('borrows', 'statuses'));
    }

    /**
     * Show the form for creating a new borrow entry.
     *
     * @return \Illuminate\View\View
     */
    /**
     * Show the form for creating a new borrow entry.
     * Only shows books that are currently available for borrowing.
     *
     * @return \Illuminate\View\View
     */
    public function create(): \Illuminate\View\View
    {
        // Only get books with available quantity > 0
        $books = \App\Models\Buku::availableQtyGreaterThan(0)
            ->orderBy('title')
            ->get();
        
        // If admin is creating the borrow, show all members
        // If member is creating their own borrow, only show their record
        $user = auth()->user();
        if ($user && $user->role === 'Member' && $user->member) {
            $members = Member::where('member_id', $user->member->member_id)->get();
        } else {
            $members = Member::orderBy('name')->get();
        }
        
        // Get today's date for the form
        $today = Carbon::now()->format('Y-m-d');
        
        // Check if the book_id was passed and pre-select that book
        $book = null;
        $bookId = request()->query('book_id');
        
        if ($bookId) {
            $book = Buku::find($bookId);
            if (!$book || $book->available_qty <= 0) {
                return redirect()->back()->with('error', 'Book is not available for borrowing.');
            }
        }
        
        // For member users, pre-select their own member record
        $user = auth()->user();
        $member = null;
        
        if ($user->role === 'Member' && $user->member) {
            $member = $user->member;
        }
        
        return \view('vendor.argon.borrow.create', compact('books', 'members', 'today', 'book', 'member'));
    }

    /**
     * Store a newly created borrow entry in storage.
     *
     * @param  BorrowRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException If book is no longer available
     */
    public function store(BorrowRequest $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validated();
        assert(is_array($validated));
        
        // Get book ID and cast to int if needed
        $bookId = $validated['book_id'];
        if (!is_int($bookId) && is_numeric($bookId)) {
            $bookId = (int) $bookId;
        }
        assert(is_int($bookId));
        
        try {
            // Use database transaction to prevent race conditions
            return DB::transaction(function () use ($validated, $bookId) {
                // Lock the book row to prevent concurrent borrowing of the same book
                $book = Buku::where('book_id', $bookId)->lockForUpdate()->first();
                
                // Validate book availability within the transaction
                if (!$book || $book->available_qty < 1) {
                    throw ValidationException::withMessages([
                        'book_id' => 'This book is no longer available for borrowing.'
                    ]);
                }
                
                // Create borrow request
                $borrow = new Log_Pinjam_Buku($validated);
                $borrow->status = 'pending';
                $borrow->due_date = null; // Due date will be set upon approval
                
                // Check for existing pending request from this member for this book
                $existingRequest = Log_Pinjam_Buku::where('book_id', $bookId)
                    ->where('member_id', $validated['member_id'])
                    ->where('status', 'pending')
                    ->first();
                    
                if ($existingRequest) {
                    throw ValidationException::withMessages([
                        'book_id' => 'You already have a pending request for this book.'
                    ]);
                }
                
                $borrow->save();
                
                // Log the borrow request
                Log::info("New borrow request created: ID {$borrow->loan_id}, Book ID: {$bookId}, Member ID: {$validated['member_id']}");
                
                /** @var \Illuminate\Http\RedirectResponse $resp */
                $resp = redirect()->route('borrow.index')->with('status', 'Borrow request submitted and pending approval.');
                return $resp;
            });
        } catch (ValidationException $e) {
            throw $e; // Re-throw validation exceptions to be handled by Laravel
        } catch (\Exception $e) {
            Log::error("Error creating borrow request: {$e->getMessage()}");
            /** @var \Illuminate\Http\RedirectResponse $resp */
            $resp = redirect()->back()->withErrors(['general' => 'An error occurred while processing your request. Please try again.']);
            return $resp;
        }
    }

    /**
     * Display the specified borrow log.
     *
     * @param $loan_id
     * @return \Illuminate\View\View
     */
    public function show($loan_id): \Illuminate\View\View
    {
        $borrow = Log_Pinjam_Buku::findOrFail($loan_id);
        return \view('vendor.argon.borrow.show', compact('borrow'));
    }

    /**
     * Show the form for editing the specified borrow log.
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function edit(int $id): \Illuminate\View\View
    {
        $borrow = Log_Pinjam_Buku::findOrFail($id);
        $books = Buku::all();
        $members = Member::all();
        return \view('vendor.argon.borrow.edit', compact('borrow', 'books', 'members'));
    }

    /**
     * Update the specified borrow entry in storage.
     *
     * @param  Request  $request
     * @param  Log_Pinjam_Buku  $borrow
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $loan_id): RedirectResponse
    {
        // Find the borrow record by loan_id
        $borrow = Log_Pinjam_Buku::findOrFail($loan_id);
        
        $validated = $request->validate([
            'book_id' => 'required|exists:buku,book_id',
            'member_id' => 'required|exists:member,member_id',
            'borrow_date' => 'required|date',
            'return_date' => 'nullable|date|after_or_equal:borrow_date',
            'status' => 'required|in:pending,approved,returned,overdue,rejected',
            'due_date' => 'nullable|date|after_or_equal:borrow_date',
        ]);

        DB::transaction(function () use ($borrow, $validated) {
            $oldStatus = $borrow->status;
            $oldBookId = $borrow->book_id;

            $newStatus = $validated['status'];
            $newBookId = (int)$validated['book_id'];

            // 1. Update the borrow record itself (fill and save later for atomicity with stock)
            $borrow->fill($validated);

            // 2. Due Date Logic based on new status
            if ($newStatus === 'approved') {
                if (empty($validated['due_date'])) { // If due_date was not explicitly set in the form
                    $borrow->due_date = Carbon::parse($borrow->borrow_date)->addDays(14);
                } else {
                    $borrow->due_date = Carbon::parse($validated['due_date']);
                }
            } elseif ($newStatus !== 'overdue' && $newStatus !== 'returned') {
                $borrow->due_date = null;
            }
            // If status is 'returned' and return_date is not set via form, set it now
            if ($newStatus === 'returned' && empty($borrow->return_date) && empty($validated['return_date'])) {
                $borrow->return_date = Carbon::now();
            }
            
            $borrow->save(); // Save changes to borrow record

            // 3. Stock Adjustment Logic
            $bookChanged = ($oldBookId !== $newBookId);
            $statusChangedToApproved = ($oldStatus !== 'approved' && $newStatus === 'approved');
            $statusChangedFromApproved = ($oldStatus === 'approved' && $newStatus !== 'approved');

            if ($bookChanged || $statusChangedToApproved || $statusChangedFromApproved) {
                $oldBook = Buku::find($oldBookId);
                $newBook = ($bookChanged) ? Buku::find($newBookId) : $oldBook;

                // Revert stock for the old book if its loan status was 'approved'
                if ($oldStatus === 'approved' && $oldBook) {
                    $oldBook->borrowed_qty = max(0, $oldBook->borrowed_qty - 1);
                    $oldBook->available_qty += 1;
                    $oldBook->save();
                }

                // Apply stock for the new book if its new loan status is 'approved'
                if ($newStatus === 'approved' && $newBook) {
                    // Check availability only if it's a new book for an approved loan, or status changed from non-approved to approved
                    if ($bookChanged || $statusChangedToApproved) {
                         if ($newBook->available_qty < 1) {
                            throw ValidationException::withMessages(['book_id' => 'The selected book (' . $newBook->title . ') is not available.']);
                        }
                        $newBook->borrowed_qty += 1;
                        $newBook->available_qty = max(0, $newBook->available_qty - 1);
                        $newBook->save();
                    }
                }
            }
        });

        return redirect()->route('borrow.index')->with('status', 'Borrow record updated successfully.');
    }

    /**
     * Approve the specified borrow request.
     *
     * @param  int  $loan_id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function approve($loan_id)
    {
        $borrow = Log_Pinjam_Buku::findOrFail($loan_id);

        if ($borrow->status !== 'pending') {
            if (request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Only pending borrows can be approved.'
                ]);
            }
            return redirect()->route('borrow.index')->with('error', 'Only pending borrows can be approved.');
        }

        $book = Buku::find($borrow->book_id);
        if (!$book) {
            if (request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Book associated with this loan not found.'
                ]);
            }
            return redirect()->route('borrow.index')->with('error', 'Book associated with this loan not found.');
        }
        if ($book->available_qty < 1) {
            if (request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Book is no longer available for borrowing.'
                ]);
            }
            return redirect()->route('borrow.index')->with('error', 'Book is no longer available for borrowing.');
        }

        DB::transaction(function () use ($borrow, $book) {
            $borrow->status = 'approved';
            $borrow->due_date = Carbon::now()->addDays(14); // Standard 14-day loan
            $borrow->save();

            // Update book quantities
            $book->available_qty = max(0, $book->available_qty - 1);
            $book->borrowed_qty += 1;
            $book->save();
        });
        
        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Borrow request approved successfully.'
            ]);
        }
        
        /** @var \Illuminate\Http\RedirectResponse $resp */
        $resp = redirect()->route('borrow.index')->with('status', 'Borrow request approved.');
        return $resp;
    }

    /**
     * Reject the specified borrow request.
     *
     * @param  int  $loan_id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function reject($loan_id)
    {
        $borrow = Log_Pinjam_Buku::findOrFail($loan_id);
        if ($borrow->status !== 'pending') {
            if (request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Only pending borrows can be rejected.'
                ]);
            }
            return redirect()->route('borrow.index')->with('error', 'Only pending borrows can be rejected.');
        }
        $borrow->status = 'rejected';
        $borrow->save();
        
        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Borrow request rejected successfully.'
            ]);
        }
        
        /** @var \Illuminate\Http\RedirectResponse $resp */
        $resp = redirect()->route('borrow.index')->with('status', 'Borrow request rejected.');
        return $resp;
    }

    /**
     * Process a borrow request from the modal form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Buku  $book
     * @return \Illuminate\Http\RedirectResponse
     */
    public function request(Request $request, Buku $book): \Illuminate\Http\RedirectResponse
    {
        // Validate the request
        $validated = $request->validate([
            'member_id' => 'required|exists:member,member_id',
            'borrow_date' => 'required|date',
            'due_date' => 'required|date|after:borrow_date',
        ]);
        
        // Check if book is available
        if ($book->available_qty <= 0) {
            return back()->with('error', 'This book is not available for borrowing.');
        }
        
        // Check for existing borrow request
        $existingRequest = Log_Pinjam_Buku::where('book_id', $book->book_id)
            ->where('member_id', $validated['member_id'])
            ->whereIn('status', ['pending', 'approved', 'overdue'])
            ->first();
            
        if ($existingRequest) {
            return back()->with('error', 'You already have an active borrow request or loan for this book.');
        }
        
        // Create the borrow request
        try {
            DB::transaction(function() use ($book, $validated) {
                $borrow = new Log_Pinjam_Buku();
                $borrow->book_id = $book->book_id;
                $borrow->member_id = $validated['member_id'];
                $borrow->borrow_date = $validated['borrow_date'];
                $borrow->due_date = $validated['due_date'];
                $borrow->status = 'pending';
                $borrow->save();
            });
            
            return back()->with('success', 'Borrow request submitted successfully and is pending approval.');
        } catch (\Exception $e) {
            Log::error("Error creating borrow request: {$e->getMessage()}");
            return back()->with('error', 'An error occurred while processing your request. Please try again.');
        }
    }
    
    /**
     * Mark the specified borrow entry as returned.
     *
     * @param  int  $loan_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function returnBook($loan_id): \Illuminate\Http\RedirectResponse
    {
        $borrow = Log_Pinjam_Buku::findOrFail($loan_id);

        if ($borrow->status !== 'approved' && $borrow->status !== 'overdue') { // Allow returning overdue books
            return redirect()->route('borrow.index')->with('error', 'Only approved or overdue borrows can be returned.');
        }

        DB::transaction(function () use ($borrow) {
            $borrow->status = 'returned';
            $borrow->return_date = Carbon::now();

            // Overdue logic can be enhanced here if needed, e.g. checking if it was already 'overdue'
            // if ($borrow->return_date && $borrow->due_date && $borrow->return_date->greaterThan($borrow->due_date)) {
            //     // $borrow->status = 'overdue_returned'; // Or similar, if distinct status needed
            // }
            $borrow->save();

            $book = Buku::find($borrow->book_id);
            if ($book !== null) {
                $book->available_qty += 1;
                $book->borrowed_qty = max(0, $book->borrowed_qty - 1);
                $book->save();
            } else {
                throw new \Exception('Book not found during return process.');
            }
        });

        /** @var \Illuminate\Http\RedirectResponse $resp */
        $resp = redirect()->route('borrow.index')->with('status', 'Book returned successfully.');
        return $resp;
    }

    /**
     * Remove the specified borrow entry from storage.
     *
     * @param  Log_Pinjam_Buku  $borrow
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Log_Pinjam_Buku $borrow): \Illuminate\Http\RedirectResponse
    {
        // Only adjust stock if an 'approved' or 'overdue' (active) loan is being deleted.
        if ($borrow->status === 'approved' || $borrow->status === 'overdue') {
            DB::transaction(function () use ($borrow) {
                $book = Buku::find($borrow->book_id);
                if ($book) {
                    // Assume book is made available if an active loan record is deleted
                    $book->borrowed_qty = max(0, $book->borrowed_qty - 1);
                    $book->available_qty += 1; 
                    $book->save();
                }
                $borrow->delete();
            });
        } else {
            // For 'pending', 'rejected', or 'returned' loans, no stock adjustment is needed upon deletion.
            $borrow->delete();
        }

        return redirect()->route('borrow.index')->with('status', 'Borrow entry deleted successfully.');
    }
    
    /**
     * Check for and update overdue borrows.
     * This method should be called via a scheduled task daily.
     *
     * @return int Number of overdue borrows updated
     */
    public function checkOverdueBorrows(): int
    {
        try {
            $today = Carbon::now()->startOfDay();
            $count = 0;
            
            // Find approved borrows where due_date has passed
            $overdueBorrows = Log_Pinjam_Buku::where('status', 'approved')
                ->whereNotNull('due_date')
                ->where('due_date', '<', $today)
                ->get();
            
            foreach ($overdueBorrows as $borrow) {
                DB::transaction(function () use ($borrow) {
                    $borrow->status = 'overdue';
                    $borrow->overdue_count = ($borrow->overdue_count ?? 0) + 1;
                    $borrow->save();
                    
                    // Optionally log or notify about overdue items
                    Log::info("Borrow ID {$borrow->loan_id} marked as overdue. Due date: {$borrow->due_date}");
                });
                $count++;
            }
            
            return $count;
        } catch (\Exception $e) {
            Log::error("Error checking overdue borrows: {$e->getMessage()}");
            return 0;
        }
    }
    
    /**
     * This is a recommendation for implementing a scheduled task to check for overdue books daily.
     * To implement this, create a command and add it to the Laravel scheduler in App\Console\Kernel.php:
     *
     * protected function schedule(Schedule $schedule)
     * {
     *     $schedule->call(function () {
     *         $controller = new \App\Http\Controllers\BorrowController();
     *         $count = $controller->checkOverdueBorrows();
     *         \Illuminate\Support\Facades\Log::info("Checked for overdue borrows. Found and updated {$count} records.");
     *     })->dailyAt('00:01');
     * }
     */
}
