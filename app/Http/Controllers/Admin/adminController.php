<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class AdminController extends Controller
{
    /**
     * Display the admin dashboard for 4ayab project.
     *
     * @return \Illuminate\View\View
     */
    public function index(): View
    {
        // ✅ يمكنك إضافة بيانات ديناميكية هنا مستقبلاً
        // $stats = \App\Models\Absence::todayStats();
        // return view('admin.dashboard', compact('stats'));

        return view('admin.dashboard');
    }
}
