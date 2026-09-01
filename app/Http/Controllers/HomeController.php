<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Video;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Tradim Home Page
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Category
        |--------------------------------------------------------------------------
        */

        $categoryId = $request->integer('category');

        /*
        |--------------------------------------------------------------------------
        | Latest Videos
        |--------------------------------------------------------------------------
        */

        $latestVideos = Video::with([
            'channel',
            'category',
        ])
            ->where('status', 'published')
            ->where('visibility', 'public')
            ->when(
                $categoryId,
                function ($query) use ($categoryId) {
                    $query->where(
                        'category_id',
                        $categoryId
                    );
                }
            )
            ->latest('published_at')
            ->paginate(24)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Trending Videos
        |--------------------------------------------------------------------------
        */

        $trendingVideos = Video::with([
            'channel',
            'category',
        ])
            ->where('status', 'published')
            ->where('visibility', 'public')
            ->orderByDesc('views_count')
            ->latest('published_at')
            ->take(12)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Popular Videos
        |--------------------------------------------------------------------------
        */

        $popularVideos = Video::with([
            'channel',
            'category',
        ])
            ->where('status', 'published')
            ->where('visibility', 'public')
            ->orderByDesc('likes_count')
            ->orderByDesc('views_count')
            ->take(12)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Categories
        |--------------------------------------------------------------------------
        */

        $categories = Category::where(
            'is_active',
            true
        )
            ->orderBy('sort_order')
            ->get();

        return view(
            'home',
            compact(
                'latestVideos',
                'trendingVideos',
                'popularVideos',
                'categories',
                'categoryId'
            )
        );
    }
}