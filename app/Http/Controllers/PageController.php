<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function show(Page $page)
    {
        // Check if page is published
        if ($page->status !== 'published') {
            abort(404);
        }

        return view('frontend.page', compact('page'));
    }
}