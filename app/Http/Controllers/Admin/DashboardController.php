<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Admin;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_pages' => Page::count(),
            'published_pages' => Page::published()->count(),
            'draft_pages' => Page::draft()->count(),
            'total_admins' => Admin::count(),
            'total_users' => User::count(),
        ];

        $recent_pages = Page::with(['creator', 'metaInformation'])
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recent_pages'));
    }
}