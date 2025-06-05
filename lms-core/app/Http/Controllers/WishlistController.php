<?php

namespace App\Http\Controllers;

use App\Http\Requests\WishlistRequest;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;

/**
 * Class WishlistController
 *
 * @package App\Http\Controllers
 */
class WishlistController extends Controller
{
    /**
     * Display a listing of the member's wishlists.
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function index(): \Illuminate\View\View|\Illuminate\Contracts\View\Factory
    {
        // Get current member's wishlists only
        $user = auth()->user();
        $memberId = $user->member->member_id ?? null;
        
        if (!$memberId) {
            return view('vendor.argon.wishlists.index', ['wishlists' => collect()]);
        }
        
        $wishlists = Wishlist::with(['buku', 'member'])
            ->where('member_id', $memberId)
            ->paginate(10);

        return view('vendor.argon.wishlists.index', compact('wishlists'));
    }
    
    /**
     * Display a listing of all wishlists for admin with filtering options.
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function adminIndex(Request $request): \Illuminate\View\View|\Illuminate\Contracts\View\Factory
    {
        $query = Wishlist::with(['buku', 'member']);
        
        // Handle search
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('buku', function($bookQuery) use ($search) {
                    $bookQuery->where('title', 'like', "%{$search}%")
                              ->orWhere('author', 'like', "%{$search}%");
                })->orWhereHas('member', function($memberQuery) use ($search) {
                    $memberQuery->where('name', 'like', "%{$search}%")
                               ->orWhere('email', 'like', "%{$search}%");
                });
            });
        }
        
        // Handle availability filter
        if ($request->has('availability') && !empty($request->availability)) {
            if ($request->availability == 'available') {
                $query->whereHas('buku', function($q) {
                    $q->where('available_qty', '>', 0);
                });
            } else if ($request->availability == 'unavailable') {
                $query->whereHas('buku', function($q) {
                    $q->where('available_qty', 0);
                });
            }
        }
        
        $wishlists = $query->paginate(10)->appends($request->all());
        return view('vendor.argon.wishlists.admin-index', compact('wishlists'));
    }

    /**
     * Store a newly created wishlist item in storage or remove if it already exists (toggle).
     *
     * @param  WishlistRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(WishlistRequest $request)
    {
        /** @var array<string, mixed> $attributes */
        $attributes = $request->only(['member_id', 'book_id']);
        
        // Check if wishlist item already exists
        $existingWishlist = Wishlist::where('book_id', $attributes['book_id'])
            ->where('member_id', $attributes['member_id'])
            ->first();
            
        $message = '';
        
        if ($existingWishlist) {
            // If it exists, remove it (toggle off)
            $existingWishlist->delete();
            $message = 'Book removed from wishlist.';
        } else {
            // If it doesn't exist, create it (toggle on)
            Wishlist::create($attributes);
            $message = 'Book added to wishlist.';
        }

        // Return to the previous page with status message
        return back()->with('status', $message);
    }

    /**
     * Remove the specified wishlist item from storage.
     *
     * @param  Wishlist  $wishlist
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Wishlist $wishlist): RedirectResponse
    {
        $wishlist->delete();

        return Redirect::to('/wishlists')->with('status', 'Wishlist item removed.');
    }
    
    /**
     * Create a borrow request directly from a wishlist item.
     *
     * @param Wishlist $wishlist
     * @return RedirectResponse
     */
    public function borrowFromWishlist(Wishlist $wishlist): RedirectResponse
    {
        // Check if the book is available
        $book = \App\Models\Buku::find($wishlist->book_id);
        if (!$book || $book->available_qty < 1) {
            return redirect()->back()->withErrors(['book_id' => 'This book is not available for borrowing.']);
        }
        
        // Check if the member already has a pending request for this book
        $existingRequest = \App\Models\Log_Pinjam_Buku::where('book_id', $wishlist->book_id)
            ->where('member_id', $wishlist->member_id)
            ->where('status', 'pending')
            ->first();
            
        if ($existingRequest) {
            return redirect()->back()->withErrors(['book_id' => 'You already have a pending request for this book.']);
        }
        
        // Create a new borrow request
        try {
            DB::transaction(function () use ($wishlist) {
                \App\Models\Log_Pinjam_Buku::create([
                    'book_id' => $wishlist->book_id,
                    'member_id' => $wishlist->member_id,
                    'borrow_date' => now()->format('Y-m-d'),
                    'status' => 'pending',
                ]);
            });
            
            // Optionally remove from wishlist after creating borrow request
            // $wishlist->delete();
            
            return redirect()->route('borrow.index')->with('status', 'Borrow request created from your wishlist item.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Error creating borrow from wishlist: {$e->getMessage()}");
            return redirect()->back()->withErrors(['general' => 'An error occurred while processing your request.']);
        }
    }
}
