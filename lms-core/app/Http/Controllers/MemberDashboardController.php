<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Log_Pinjam_Buku;
use App\Models\Bookmark;
use App\Models\Wishlist;
use App\Models\Pengumuman;
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\View\Factory;
use Illuminate\Support\Facades\DB;

/**
 * Class MemberDashboardController
 *
 * @package App\Http\Controllers
 */
class MemberDashboardController extends Controller
{
    /**
     * Show the member dashboard with integrated view of all activities.
     *
     * @return View|Factory
     */
    public function index(): View|Factory
    {
        $user = auth()->user();
        $memberId = $user->member->member_id ?? null;
        
        if (!$memberId) {
            // If no member profile is linked to this user account
            return view('vendor.argon.pages.member-dashboard', [
                'activeBorrows' => collect(),
                'recentlyReturned' => collect(),
                'wishlistItems' => collect(),
                'bookmarks' => collect(),
                'announcements' => collect(),
                'recommendedBooks' => collect(),
            ]);
        }
        
        // Active borrows (approved or overdue)
        $activeBorrows = Log_Pinjam_Buku::with('buku')
            ->where('member_id', $memberId)
            ->whereIn('status', ['approved', 'overdue'])
            ->orderBy('due_date')
            ->take(5)
            ->get();
        
        // Recently returned books
        $recentlyReturned = Log_Pinjam_Buku::with('buku')
            ->where('member_id', $memberId)
            ->where('status', 'returned')
            ->orderByDesc('return_date')
            ->take(5)
            ->get();
        
        // Wishlist items
        $wishlistItems = Wishlist::with('buku')
            ->where('member_id', $memberId)
            ->take(5)
            ->get();
        
        // Bookmarks
        $bookmarks = Bookmark::with('buku')
            ->where('member_id', $memberId)
            ->take(5)
            ->get();
        
        // Recent announcements
        $announcements = Pengumuman::orderByDesc('created_at')
            ->take(3)
            ->get();
        
        // Book recommendations based on borrowing history
        $recommendedBooks = $this->getRecommendedBooks($memberId);
        
        // Stats for the member
        $stats = [
            'totalBorrowed' => Log_Pinjam_Buku::where('member_id', $memberId)->count(),
            'currentlyBorrowed' => $activeBorrows->count(),
            'overdue' => Log_Pinjam_Buku::where('member_id', $memberId)->where('status', 'overdue')->count(),
        ];
        
        return view('vendor.argon.pages.member-dashboard', compact(
            'activeBorrows',
            'recentlyReturned',
            'wishlistItems',
            'bookmarks',
            'announcements',
            'recommendedBooks',
            'stats'
        ));
    }
    
    /**
     * Get book recommendations based on member's borrowing history and categories.
     *
     * @param int $memberId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getRecommendedBooks(int $memberId)
    {
        // Get categories of books the member has borrowed
        $borrowedCategories = Log_Pinjam_Buku::where('member_id', $memberId)
            ->join('buku', 'log_pinjam_buku.book_id', '=', 'buku.book_id')
            ->select('buku.category_id')
            ->distinct()
            ->pluck('category_id')
            ->toArray();
        
        // Get books from those categories that the member hasn't borrowed yet
        $borrowedBookIds = Log_Pinjam_Buku::where('member_id', $memberId)
            ->pluck('book_id')
            ->toArray();
        
        $recommendedBooks = Buku::whereIn('category_id', $borrowedCategories)
            ->whereNotIn('book_id', $borrowedBookIds)
            ->where('available_qty', '>', 0)
            ->orderBy(DB::raw('RAND()'))
            ->take(5)
            ->get();
        
        // If we don't have enough recommendations, add some popular books
        if ($recommendedBooks->count() < 5) {
            $popularBookIds = Log_Pinjam_Buku::select('book_id', DB::raw('COUNT(*) as borrow_count'))
                ->groupBy('book_id')
                ->orderByDesc('borrow_count')
                ->pluck('book_id')
                ->toArray();
            
            $additionalBooks = Buku::whereIn('book_id', $popularBookIds)
                ->whereNotIn('book_id', $borrowedBookIds)
                ->whereNotIn('book_id', $recommendedBooks->pluck('book_id')->toArray())
                ->where('available_qty', '>', 0)
                ->take(5 - $recommendedBooks->count())
                ->get();
            
            $recommendedBooks = $recommendedBooks->concat($additionalBooks);
        }
        
        return $recommendedBooks;
    }
}
