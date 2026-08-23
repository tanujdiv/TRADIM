<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Video;

class HomeController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Tradim Home
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $videos = Video::with([
            'channel',
            'category',
        ])
            ->where(
                'status',
                'published'
            )
            ->where(
                'visibility',
                'public'
            )
            ->latest('published_at')
            ->paginate(12);


        $categories = Category::where(
            'is_active',
            true
        )
            ->orderBy('sort_order')
            ->get();


        return view(
            'home',
            compact(
                'videos',
                'categories'
            )
        );
    }
}