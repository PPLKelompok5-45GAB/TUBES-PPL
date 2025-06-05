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
     */
    /**
     * Display a listing of the current member's bookmarks.
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function index(): \Illuminate\View\View|\Illuminate\Contracts\View\Factory
    {
        // Get current member's bookmarks only
        $user = auth()->user();
        $memberId = $user->member->member_id ?? null;
        
        if (!$memberId) {
            return view('vendor.argon.bookmarks.index', ['bookmarks' => collect()]);
        }
        
        $bookmarks = Bookmark::with(['buku', 'member'])
            ->where('member_id', $memberId)
            ->paginate(10);

        return view('vendor.argon.bookmarks.index', compact('bookmarks'));
    }
    
    /**
     * Display a listing of all bookmarks for admin.
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function adminIndex(): \Illuminate\View\View|\Illuminate\Contracts\View\Factory
    {
        $bookmarks = Bookmark::with(['buku', 'member'])->paginate(10);
        return view('vendor.argon.bookmarks.admin-index', compact('bookmarks'));
    }

    /**
     * Store a newly created bookmark in storage or update if it already exists.
     *
     * @param  BookmarkRequest  $request
     * @return RedirectResponse
     */
    public function store(BookmarkRequest $request): RedirectResponse
    {
        /** @var array<string, mixed> $attributes */
        $attributes = $request->only(['member_id', 'book_id', 'notes', 'page_number']);
        
        // Check if bookmark already exists
        $existingBookmark = Bookmark::where('book_id', $attributes['book_id'])
            ->where('member_id', $attributes['member_id'])
            ->first();
        
        $message = '';
        
        if ($existingBookmark) {
            // If it exists, update it with new values
            $existingBookmark->notes = $attributes['notes'] ?? $existingBookmark->notes;
            $existingBookmark->page_number = $attributes['page_number'] ?? $existingBookmark->page_number;
            $existingBookmark->save();
            
            $message = 'Bookmark updated successfully.';
        } else {
            // If it doesn't exist, create it
            // Set added_date to current date if not provided
            if (!isset($attributes['added_date'])) {
                $attributes['added_date'] = now();
            }
            
            Bookmark::create($attributes);
            $message = 'Book added to bookmarks.';
        }

        // Return to the previous page with status message
        return back()->with('status', $message);
    }
    
    /**
     * Update the specified bookmark in storage.
     *
     * @param  BookmarkRequest  $request
     * @param  Bookmark  $bookmark
     * @return RedirectResponse
     */
    public function update(BookmarkRequest $request, Bookmark $bookmark): RedirectResponse
    {
        // Ensure the user can only update their own bookmarks
        if (auth()->user()->role === 'Member' && auth()->user()->member->member_id !== $bookmark->member_id) {
            return back()->with('error', 'You can only update your own bookmarks.');
        }
        
        // Update the bookmark with the new values
        $bookmark->page_number = $request->page_number;
        $bookmark->notes = $request->notes;
        $bookmark->save();
        
        return back()->with('status', 'Bookmark updated successfully.');
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
        // Use the route name instead of hardcoded path and ensure status message is set
        return redirect()->route('bookmarks.index')->with('status', 'Bookmark removed successfully.');
    }
}
