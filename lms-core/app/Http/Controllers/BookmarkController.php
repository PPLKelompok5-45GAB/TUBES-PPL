<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookmarkRequest;
use App\Models\Bookmark;
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;

/**
 * Class BookmarkController
 *
 * @package App\Http\Controllers
 */
class BookmarkController extends Controller
{
    /**
     * Display a listing of the bookmarks.
     *
     * @return View|Factory
     */
    public function index(): View|Factory
    {
        $bookmarks = Bookmark::with(['buku', 'member'])->paginate(10);

        return view('vendor.argon.bookmarks.index', compact('bookmarks'));
    }

    /**
     * Store a newly created bookmark in storage.
     *
     * @param  BookmarkRequest  $request
     * @return RedirectResponse
     */
    public function store(BookmarkRequest $request): RedirectResponse
    {
        /** @var array<string, mixed> $attributes */
        $attributes = $request->only(['member_id', 'book_id', 'notes']);
        Bookmark::create($attributes);

        return Redirect::to('/bookmarks')->with('status', 'Book bookmarked.');
    }

    /**
     * Remove the specified bookmark from storage.
     *
     * @param  Bookmark  $bookmark
     * @return RedirectResponse
     */
    public function destroy(Bookmark $bookmark): RedirectResponse
    {
        $bookmark->delete();
        return Redirect::to('/bookmarks')->with('status', 'Bookmark removed.');
    }
}
