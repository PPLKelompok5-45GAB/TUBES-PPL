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
        if ($request->filled('search')) {
            $search = $request->input('search', '');
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
    public function create(): \Illuminate\View\View
    {
        $books = \App\Models\Buku::where('available_qty', '>', 0)->get();
        $members = Member::all();
        return \view('vendor.argon.borrow.create', compact('books', 'members'));
    }

    /**
     * Store a newly created borrow entry in storage.
     *
     * @param  BorrowRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(BorrowRequest $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validated();
        assert(is_array($validated));
        // Check if book is available
        $bookId = $validated['book_id'];
        if (!is_int($bookId) && is_numeric($bookId)) {
            $bookId = (int) $bookId;
        }
        assert(is_int($bookId));
        $book = \App\Models\Buku::find($bookId);
        if (!$book || $book->available_qty < 1) {
            /** @var \Illuminate\Http\RedirectResponse $resp */
            $resp = redirect()->back()->withErrors(['book_id' => 'Book is not available for borrowing.']);
            return $resp;
        }
        DB::transaction(function () use ($validated, $bookId) {
            $borrow = new Log_Pinjam_Buku($validated);
            $borrow->status = 'pending';
            $borrowDate = $validated['borrow_date'] ?? null;
            assert(is_string($borrowDate) || $borrowDate instanceof \DateTimeInterface || $borrowDate === null);
            if ($borrowDate !== null) {
                $borrow->due_date = \Carbon\Carbon::parse($borrowDate)->addDays(14);
            }
            $borrow->save();
            // Update book quantity safely
            $book = \App\Models\Buku::find($bookId);
            if ($book !== null) {
                $book->available_qty = $book->available_qty - 1;
                $book->borrowed_qty = $book->borrowed_qty + 1;
                $book->save();
            } else {
                throw new \Exception('Book not found.');
            }
        });
        /** @var \Illuminate\Http\RedirectResponse $resp */
        $resp = redirect()->route('borrow.index')->with('status', 'Borrow request submitted and pending approval.');
        return $resp;
    }

    /**
     * Display the specified borrow log.
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function show(int $id): \Illuminate\View\View
    {
        $borrow = Log_Pinjam_Buku::findOrFail($id);
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
    public function update(Request $request, Log_Pinjam_Buku $borrow)
    {
        $validated = $request->validate([
            'book_id' => 'required|exists:buku,book_id',
            'member_id' => 'required|exists:member,member_id',
            'borrow_date' => 'required|date',
            'return_date' => 'nullable|date',
            'status' => 'required|in:approved,returned,overdue',
        ]);
        DB::transaction(function () use ($borrow, $validated, $request) {
            $oldBookId = $borrow->book_id;
            /** @var array<string, mixed> $attributes */
            $attributes = $request->only(['borrow_date', 'due_date', 'return_date', 'status', 'book_id', 'member_id']);
            $borrow->update($attributes);
            if ($validated['status'] === 'returned' && ! $borrow->return_date) {
                $borrow->return_date = Carbon::now();
                $borrow->save();
            }
            if ($oldBookId != $validated['book_id']) {
                $oldBookIdInt = is_int($oldBookId) ? $oldBookId : (is_numeric($oldBookId) ? (int)$oldBookId : 0);
                $newBookIdInt = is_int($validated['book_id']) ? $validated['book_id'] : (is_numeric($validated['book_id']) ? (int)$validated['book_id'] : 0);
                $oldBook = Buku::find($oldBookIdInt);
                if ($oldBook !== null) {
                    $oldBook->borrowed_qty = max(0, $oldBook->borrowed_qty - 1);
                    $oldBook->save();
                } else {
                    throw new \Exception('Book not found.');
                }
                $newBook = Buku::find($newBookIdInt);
                if ($newBook !== null) {
                    $newBook->borrowed_qty = $newBook->borrowed_qty + 1;
                    $newBook->save();
                } else {
                    throw new \Exception('Book not found.');
                }
            }
            if ($validated['status'] === 'returned') {
                $bookId = $borrow->book_id;
                if (!is_int($bookId) && is_numeric($bookId)) {
                    $bookId = (int) $bookId;
                }
                $book = Buku::find($bookId);
                if ($book !== null) {
                    $book->borrowed_qty = max(0, $book->borrowed_qty - 1);
                    $book->save();
                } else {
                    throw new \Exception('Book not found.');
                }
            }
        });
        /** @var \Illuminate\Http\RedirectResponse $resp */
        $resp = redirect()->route('borrow.index')->with('status', 'Borrow entry updated.');
        return $resp;
    }

    /**
     * Approve the specified borrow request.
     *
     * @param  int  $loan_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function approve($loan_id): \Illuminate\Http\RedirectResponse
    {
        $borrow = Log_Pinjam_Buku::findOrFail($loan_id);
        if ($borrow->status !== 'pending') {
            return redirect()->route('borrow.index')->with('error', 'Only pending borrows can be approved.');
        }
        DB::transaction(function () use ($borrow) {
            $borrow->status = 'approved';
            $borrow->due_date = Carbon::now()->addDays(14);
            $borrow->save();
            $bookId = $borrow->book_id;
            if (!is_int($bookId) && is_numeric($bookId)) {
                $bookId = (int) $bookId;
            }
            $book = Buku::find($bookId);
            if ($book !== null) {
                $book->borrowed_qty += 1;
                $book->save();
            } else {
                throw new \Exception('Book not found.');
            }
        });
        /** @var \Illuminate\Http\RedirectResponse $resp */
        $resp = redirect()->route('borrow.index')->with('status', 'Borrow request approved.');
        return $resp;
    }

    /**
     * Reject the specified borrow request.
     *
     * @param  int  $loan_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reject($loan_id): \Illuminate\Http\RedirectResponse
    {
        $borrow = Log_Pinjam_Buku::findOrFail($loan_id);
        if ($borrow->status !== 'pending') {
            return redirect()->route('borrow.index')->with('error', 'Only pending borrows can be rejected.');
        }
        $borrow->status = 'rejected';
        $borrow->save();
        /** @var \Illuminate\Http\RedirectResponse $resp */
        $resp = redirect()->route('borrow.index')->with('status', 'Borrow request rejected.');
        return $resp;
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
        if ($borrow->status !== 'approved') {
            return redirect()->route('borrow.index')->with('error', 'Only approved borrows can be returned.');
        }
        DB::transaction(function () use ($borrow) {
            $borrow->status = 'returned';
            $borrow->return_date = Carbon::now();
            if ($borrow->return_date > $borrow->due_date) {
                $borrow->overdue_count = $borrow->overdue_count + 1;
            }
            $borrow->save();
            $bookId = $borrow->book_id;
            if (!is_int($bookId) && is_numeric($bookId)) {
                $bookId = (int) $bookId;
            }
            $book = Buku::find($bookId);
            if ($book !== null) {
                $book->borrowed_qty = max(0, $book->borrowed_qty - 1);
                $book->save();
            } else {
                throw new \Exception('Book not found.');
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
        if ($borrow->status !== 'returned') {
            $bookId = $borrow->book_id;
            if (!is_int($bookId) && is_numeric($bookId)) {
                $bookId = (int) $bookId;
            }
            $book = Buku::find($bookId);
            if ($book !== null) {
                $book->borrowed_qty = max(0, $book->borrowed_qty - 1);
                $book->save();
            } else {
                /** @var \Illuminate\Http\RedirectResponse $resp */
                $resp = redirect()->back()->withErrors(['book_id' => 'Book not found.']);
                return $resp;
            }
        }
        $borrow->delete();
        /** @var \Illuminate\Http\RedirectResponse $resp */
        $resp = redirect()->route('borrow.index')->with('status', 'Borrow entry deleted.');
        return $resp;
    }
}
