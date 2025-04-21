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
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $reviews = Review_Buku::with(['buku', 'member'])->orderByDesc('created_at')->paginate(10);

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
     * @param  ReviewRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ReviewRequest $request)
    {
        /** @var array<string, mixed> $attributes */
        $attributes = $request->only(['member_id', 'book_id', 'review_text', 'review_date']);
        Review_Buku::create($attributes);

        return back()->with('status', 'Review submitted.');
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
        /** @var array<string, mixed> $attributes */
        $attributes = $request->only(['review_text', 'review_date']);
        $review->update($attributes);
        return back()->with('status', 'Review updated.');
    }

    /**
     * Remove the specified review from storage.
     *
     * @param  Review_Buku  $review
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Review_Buku $review)
    {
        $review->delete();

        return back()->with('status', 'Review removed.');
    }
}
