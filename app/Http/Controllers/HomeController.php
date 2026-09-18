<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Services\RecommendationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Tradim Home Page
    |--------------------------------------------------------------------------
    */

    public function index(
        Request $request,
        RecommendationService $recommendationService
    ) {
        /*
        |--------------------------------------------------------------------------
        | Category Filter
        |--------------------------------------------------------------------------
        */

        $categoryId = $request->integer('category');

        $selectedCategory = null;

        if ($categoryId) {
            $selectedCategory = Category::query()
                ->where('is_active', true)
                ->where('id', $categoryId)
                ->first();
        }

        /*
        |--------------------------------------------------------------------------
        | Recommendation Sections
        |--------------------------------------------------------------------------
        */

        $recommendedVideos = $recommendationService
            ->recommended(12);

        $trendingVideos = $recommendationService
            ->trending(12);

        $popularVideos = $recommendationService
            ->popular(12);

        $latestVideos = $recommendationService
            ->latest(24);

        /*
        |--------------------------------------------------------------------------
        | Subscription Videos
        |--------------------------------------------------------------------------
        */

        $subscribedVideos = collect();

        if (Auth::check()) {
            $subscribedVideos = $recommendationService
                ->subscribed(12);
        }

        /*
        |--------------------------------------------------------------------------
        | Recently Watched
        |--------------------------------------------------------------------------
        */

        $recentlyWatched = collect();

        if (Auth::check()) {
            $recentlyWatched = $recommendationService
                ->recentlyWatched(8);
        }

        /*
        |--------------------------------------------------------------------------
        | Categories
        |--------------------------------------------------------------------------
        */

        $categories = Category::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Category Filter Videos
        |--------------------------------------------------------------------------
        */

        $categoryVideos = collect();

        if ($selectedCategory) {
            $categoryVideos = $recommendationService
                ->categoryVideos(
                    $selectedCategory->id,
                    24
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Category Sections
        |--------------------------------------------------------------------------
        */

        $categorySections = collect();

        foreach ($categories->take(6) as $category) {
            $videos = $recommendationService
                ->categoryVideos(
                    $category->id,
                    8
                );

            if ($videos->isNotEmpty()) {
                $categorySections->push([
                    'category' => $category,
                    'videos' => $videos,
                ]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | View
        |--------------------------------------------------------------------------
        */

        return view(
            'home',
            compact(
                'recommendedVideos',
                'subscribedVideos',
                'recentlyWatched',
                'trendingVideos',
                'popularVideos',
                'latestVideos',
                'categories',
                'categoryId',
                'selectedCategory',
                'categoryVideos',
                'categorySections'
            )
        );
    }
}