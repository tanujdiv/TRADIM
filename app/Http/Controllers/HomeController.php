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
        | Categories
        |--------------------------------------------------------------------------
        */

        $categories = Category::where(
            'is_active',
            true
        )
            ->orderBy('sort_order')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Videos Query
        |--------------------------------------------------------------------------
        */

        $videoQuery = Video::with([
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
            );


        /*
        |--------------------------------------------------------------------------
        | Category Filter
        |--------------------------------------------------------------------------
        */

        if (
            $request->filled('category')
        ) {

            $videoQuery->where(
                'category_id',
                $request->category
            );

        }


        /*
        |--------------------------------------------------------------------------
        | Latest Videos
        |--------------------------------------------------------------------------
        */

        $videos = $videoQuery
            ->latest('published_at')
            ->paginate(12)
            ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | Trending Videos
        |--------------------------------------------------------------------------
        */

        $trendingQuery = Video::with([
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
            );


        if (
            $request->filled('category')
        ) {

            $trendingQuery->where(
                'category_id',
                $request->category
            );

        }


        $trendingVideos = $trendingQuery
            ->orderByDesc(
                'views_count'
            )
            ->take(8)
            ->get();


        return view(
            'home',
            compact(
                'categories',
                'videos',
                'trendingVideos'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Search
    |--------------------------------------------------------------------------
    */

    public function search(
        Request $request
    ) {

        $query = trim(
            $request->input(
                'q',
                ''
            )
        );


        /*
        |--------------------------------------------------------------------------
        | Empty Search
        |--------------------------------------------------------------------------
        */

        if ($query === '') {

            return redirect()
                ->route('home');

        }


        /*
        |--------------------------------------------------------------------------
        | Search Videos
        |--------------------------------------------------------------------------
        */

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
            ->where(function ($builder) use ($query) {

                $builder
                    ->where(
                        'title',
                        'like',
                        '%' . $query . '%'
                    )
                    ->orWhere(
                        'description',
                        'like',
                        '%' . $query . '%'
                    );

            })
            ->latest('published_at')
            ->paginate(12)
            ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | Search Channels
        |--------------------------------------------------------------------------
        */

        $channels = \App\Models\Channel::where(
            'is_active',
            true
        )
            ->where(function ($builder) use ($query) {

                $builder
                    ->where(
                        'name',
                        'like',
                        '%' . $query . '%'
                    )
                    ->orWhere(
                        'handle',
                        'like',
                        '%' . $query . '%'
                    );

            })
            ->orderByDesc(
                'subscriber_count'
            )
            ->take(6)
            ->get();


        return view(
            'search',
            compact(
                'query',
                'videos',
                'channels'
            )
        );
    }
}