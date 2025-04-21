<?php

namespace App\Http\Controllers;

class MemberDashboardController extends Controller
{
    /**
     * Show the member dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // You can add member-specific logic here
        return view('vendor.argon.pages.member-dashboard');
    }
}
