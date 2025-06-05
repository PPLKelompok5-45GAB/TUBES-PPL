<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Member;
use App\Models\Log_Pinjam_Buku;
use App\Models\Kategori;
use App\Models\Pengumuman;
use App\Models\Review_Buku;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\View\Factory;
use Illuminate\Support\Facades\DB;

/**
 * Class AdminDashboardController
 *
 * @package App\Http\Controllers
 */
class AdminDashboardController extends Controller
{
    /**
     * Show the admin dashboard with analytics and management capabilities.
     *
     * @return View|Factory
     */
    public function index(): View|Factory
    {
        // System statistics
        $stats = [
            'totalBooks' => Buku::count(),
            'availableBooks' => Buku::sum('available_qty'),
            'borrowedBooks' => Buku::sum('borrowed_qty'),
            'totalMembers' => Member::count(),
            'activeMembers' => Member::where('status', 'active')->count(),
            'pendingBorrows' => Log_Pinjam_Buku::where('status', 'pending')->count(),
            'overdueBorrows' => Log_Pinjam_Buku::where('status', 'overdue')->count(),
        ];
        
        // Recent activity
        $recentBorrows = Log_Pinjam_Buku::with(['buku', 'member'])
            ->orderByDesc('created_at')
            ->take(5)
            ->get();
            
        $recentReturns = Log_Pinjam_Buku::with(['buku', 'member'])
            ->where('status', 'returned')
            ->orderByDesc('return_date')
            ->take(5)
            ->get();
            
        // Books with low stock
        $lowStockBooks = Buku::where('available_qty', '<', 3)
            ->where('total_stock', '>', 0)
            ->orderBy('available_qty')
            ->take(5)
            ->get();
            
        // Most popular books (most borrowed)
        $popularBooks = Log_Pinjam_Buku::select('book_id', DB::raw('COUNT(*) as borrow_count'))
            ->groupBy('book_id')
            ->orderByDesc('borrow_count')
            ->take(5)
            ->get()
            ->map(function ($item) {
                $book = Buku::find($item->book_id);
                if ($book) {
                    $book->borrow_count = $item->borrow_count;
                }
                return $book;
            })
            ->filter();
            
        // Recent reviews
        $recentReviews = Review_Buku::with(['buku', 'member'])
            ->orderByDesc('created_at')
            ->take(5)
            ->get();
            
        // Borrow trends (last 7 days)
        $startDate = Carbon::now()->subDays(6)->startOfDay();
        $endDate = Carbon::now()->endOfDay();
        
        $borrowTrends = [];
        $returnTrends = [];
        
        for ($date = clone $startDate; $date <= $endDate; $date->addDay()) {
            $day = $date->format('Y-m-d');
            
            $borrowCount = Log_Pinjam_Buku::whereDate('created_at', $day)->count();
            $returnCount = Log_Pinjam_Buku::whereDate('return_date', $day)->count();
            
            $borrowTrends[$date->format('D')] = $borrowCount;
            $returnTrends[$date->format('D')] = $returnCount;
        }
        
        // Categories
        $categories = Kategori::withCount('bukus')->get();
        
        // Pending actions that require admin attention
        $pendingActions = [
            'pendingBorrows' => Log_Pinjam_Buku::with(['buku', 'member'])
                ->where('status', 'pending')
                ->orderBy('created_at')
                ->take(5)
                ->get(),
            'overdueBorrows' => Log_Pinjam_Buku::with(['buku', 'member'])
                ->where('status', 'overdue')
                ->orderBy('due_date')
                ->take(5)
                ->get(),
        ];
        
        return view('vendor.argon.pages.admin-dashboard', compact(
            'stats',
            'recentBorrows',
            'recentReturns',
            'lowStockBooks',
            'popularBooks',
            'recentReviews',
            'borrowTrends',
            'returnTrends',
            'categories',
            'pendingActions'
        ));
    }
    
    /**
     * Generate a report of library activity for a given time period.
     *
     * @param string $period daily|weekly|monthly|yearly
     * @return View|Factory
     */
    public function activityReport(string $period = 'monthly'): View|Factory
    {
        $now = Carbon::now();
        $startDate = null;
        $groupByFormat = null;
        $periodLabel = '';
        
        switch ($period) {
            case 'daily':
                $startDate = $now->copy()->subDays(30);
                $groupByFormat = 'Y-m-d';
                $periodLabel = 'Daily';
                break;
            case 'weekly':
                $startDate = $now->copy()->subWeeks(12);
                $groupByFormat = 'W';
                $periodLabel = 'Weekly';
                break;
            case 'yearly':
                $startDate = $now->copy()->subYears(5);
                $groupByFormat = 'Y';
                $periodLabel = 'Yearly';
                break;
            case 'monthly':
            default:
                $startDate = $now->copy()->subMonths(12);
                $groupByFormat = 'Y-m';
                $periodLabel = 'Monthly';
                break;
        }
        
        // Borrow statistics
        $borrowStats = Log_Pinjam_Buku::select(
                DB::raw("DATE_FORMAT(created_at, '{$groupByFormat}') as period"),
                DB::raw('COUNT(*) as borrow_count')
            )
            ->where('created_at', '>=', $startDate)
            ->groupBy('period')
            ->orderBy('period')
            ->get()
            ->pluck('borrow_count', 'period')
            ->toArray();
            
        // Return statistics
        $returnStats = Log_Pinjam_Buku::select(
                DB::raw("DATE_FORMAT(return_date, '{$groupByFormat}') as period"),
                DB::raw('COUNT(*) as return_count')
            )
            ->where('return_date', '>=', $startDate)
            ->groupBy('period')
            ->orderBy('period')
            ->get()
            ->pluck('return_count', 'period')
            ->toArray();
            
        // Overdue statistics
        $overdueStats = Log_Pinjam_Buku::select(
                DB::raw("DATE_FORMAT(due_date, '{$groupByFormat}') as period"),
                DB::raw('COUNT(*) as overdue_count')
            )
            ->where('due_date', '>=', $startDate)
            ->where(function ($query) {
                $query->where('status', 'overdue')
                    ->orWhere(function ($q) {
                        $q->where('status', 'returned')
                            ->whereRaw('return_date > due_date');
                    });
            })
            ->groupBy('period')
            ->orderBy('period')
            ->get()
            ->pluck('overdue_count', 'period')
            ->toArray();
            
        return view('vendor.argon.pages.admin-reports', compact(
            'periodLabel',
            'period',
            'borrowStats',
            'returnStats',
            'overdueStats'
        ));
    }
}
