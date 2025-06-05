<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReviewRequest;
use App\Models\Review_Buku;
use Illuminate\Http\Request;

/**
 * Class ReviewController
 *
 * @package App\Http\Controllers
 */
class ReviewController extends Controller
{
    /**
     * Display a listing of the reviews.
     *
     * @param  Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = \App\Models\Review_Buku::query()->with(['buku', 'member']);
        $user = auth()->user();
        
        // Only filter by member_id if the user is a Member (Admin should see all reviews)
        if ($user && $user->role === 'Member' && $user->member) {
            $query->where('member_id', $user->member->member_id);
        }
        
        if ($request->filled('search')) {
            $search = $request->input('search', '');
            $query->where(function ($q) use ($search) {
                $q->whereHas('buku', function ($qb) use ($search) {
                    $qb->where('title', 'like', "%$search%")
                        ->orWhere('author', 'like', "%$search%");
                })
                ->orWhereHas('member', function ($qm) use ($search) {
                    $qm->where('name', 'like', "%$search%")
                        ->orWhere('email', 'like', "%$search%");
                })
                ->orWhere('review_text', 'like', "%$search%");
            });
        }
        
        $reviews = $query->orderByDesc('created_at')->paginate(10)->appends($request->all());
        
        // Determine which view to use based on user role and route
        $routeName = request()->route()->getName();
        
        if ($routeName === 'admin.reviews.index') {
            return view('vendor.argon.reviews.admin-index', compact('reviews'));
        }
        
        return view('vendor.argon.reviews.index', compact('reviews'));
    }

    /**
     * Show the form for creating a new review.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $books = \App\Models\Buku::all();
        $members = \App\Models\Member::all();
        return view('vendor.argon.reviews.create', compact('books', 'members'));
    }

    /**
     * Store a newly created review in storage.
     *
     * @param  Request  $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'book_id' => 'required|exists:buku,book_id',
            'member_id' => 'required|exists:member,member_id',
            'rating' => 'required|numeric|min:1|max:5',
            'review_text' => 'required|string|max:1000',
        ]);
        
        // Security check: Ensure the member_id belongs to the current user if not admin
        $user = auth()->user();
        if ($user->role !== 'Admin' && $user->member && $user->member->member_id != $validated['member_id']) {
            return redirect()->back()->withErrors([
                'member_id' => 'You can only submit reviews for your own account.'
            ])->withInput();
        }
        
        // Verify that the member has borrowed and returned this book
        $hasBorrowed = $this->verifyBorrowedByMember($validated['book_id'], $validated['member_id']);
        
        if (!$hasBorrowed) {
            // Check if currently borrowing
            $currentlyBorrowing = \App\Models\Log_Pinjam_Buku::where('book_id', $validated['book_id'])
                ->where('member_id', $validated['member_id'])
                ->whereIn('status', ['pending', 'approved', 'overdue'])
                ->exists();
                
            $message = $currentlyBorrowing 
                ? 'You can only review this book after you return it.' 
                : 'You can only review books that you have borrowed and returned.';
                
            return redirect()->back()->withErrors([
                'book_id' => $message
            ])->withInput();
        }
        
        // Check for existing review
        $existingReview = Review_Buku::where('book_id', $validated['book_id'])
            ->where('member_id', $validated['member_id'])
            ->first();
            
        if ($existingReview) {
            return redirect()->back()->withErrors([
                'book_id' => 'You have already reviewed this book. You can edit your existing review instead.'
            ])->withInput();
        }

        // Add review date and create review
        $validated['review_date'] = now();
        Review_Buku::create($validated);

        return redirect()->route('reviews.index')->with('status', 'Review added successfully.');
    }

    /**
     * Display the specified review.
     *
     * @param  Review_Buku  $review
     * @return \Illuminate\View\View
     */
    public function show(Review_Buku $review)
    {
        return view('vendor.argon.reviews.show', compact('review'));
    }

    /**
     * Update the specified review in storage.
     *
     * @param  ReviewRequest  $request
     * @param  Review_Buku  $review
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ReviewRequest $request, Review_Buku $review)
    {
        // Security check: Ensure the user can only edit their own reviews unless they're an admin
        $user = auth()->user();
        if ($user->role !== 'Admin' && $user->member && $user->member->member_id != $review->member_id) {
            return redirect()->back()->withErrors([
                'error' => 'You can only edit your own reviews.'
            ]);
        }
        
        $attributes = $request->only(['review_text', 'rating']);
        $attributes['review_date'] = now(); // Update the review date
        
        $review->update($attributes);
        return back()->with('status', 'Review updated successfully.');
    }

    /**
     * Remove the specified review from storage.
     *
     * @param  Review_Buku  $review
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Review_Buku $review)
    {
        // Security check: Ensure the user can only delete their own reviews unless they're an admin
        $user = auth()->user();
        if ($user->role !== 'Admin' && $user->member && $user->member->member_id != $review->member_id) {
            return redirect()->back()->withErrors([
                'error' => 'You can only delete your own reviews.'
            ]);
        }
        
        $review->delete();

        return back()->with('status', 'Review deleted successfully.');
    }
    
    /**
     * Verify that a member has borrowed and returned a specific book.
     *
     * @param int $bookId
     * @param int $memberId
     * @return bool
     */
    protected function verifyBorrowedByMember($bookId, $memberId): bool
    {
        return \App\Models\Log_Pinjam_Buku::where('book_id', $bookId)
            ->where('member_id', $memberId)
            ->where('status', 'returned')
            ->exists();
    }
}
