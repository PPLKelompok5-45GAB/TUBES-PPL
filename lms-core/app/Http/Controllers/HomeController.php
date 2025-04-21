<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Log_Pinjam_Buku;
use App\Models\Member;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $books_count = Buku::count();
        $members_count = Member::count();
        $borrowed_count = Log_Pinjam_Buku::where('status', 'approved')->count();
        $overdue_count = Log_Pinjam_Buku::where('status', 'overdue')->count();
        $recent_borrows = Log_Pinjam_Buku::with(['member', 'buku'])->latest('updated_at')->limit(5)->get();

        return view('vendor.argon.dashboard', compact('books_count', 'members_count', 'borrowed_count', 'overdue_count', 'recent_borrows'));
    }
}
