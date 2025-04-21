<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Member;
use App\Models\Log_Pinjam_Buku;
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\View\Factory;

/**
 * Class AdminDashboardController
 *
 * @package App\Http\Controllers
 */
class AdminDashboardController extends Controller
{
    /**
     * Show the admin dashboard.
     *
     * @return View|Factory|string
     */
    public function index(): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|string
    {
        // You can add admin-specific logic here
        return view('vendor.argon.pages.admin-dashboard');
    }
}
