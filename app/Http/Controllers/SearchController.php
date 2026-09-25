<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Channel;
use App\Models\Video;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Advanced Search
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $query = trim(
            (string) $request->input('q', '')
        );

        $type = $request->input('type', 'all');

        if (!in_array($type, ['all', 'videos', 'channels'], true)) {
            $type = 'all';
        }

        $category = $request->input('category');

        $duration = $request->input('duration', 'all');

        if (
            !in_array($duration, [
                'all',
                'short',
                'medium',
                'long',
            ], true)
        ) {
            $duration = 'all';
        }

        $date = $request->input('date', 'all');

        if (
            !in_array($date, [
                'all',
                'today',
                'week',
                'month',
                'year',
            ], true)
        ) {
            $date = 'all';
        }

        $sort = $request->input('sort', 'relevance');

        if (
            !in_array($sort, [
                'relevance',
                'latest',
                'views',
                'likes',
            ], true)
        ) {
            $sort = 'relevance';
        }

        $perPage = (int) $request->input('per_page', 12);

        if (!in_array($perPage, [12, 24, 48], true)) {
            $perPage = 12;
        }

        $categories = Category::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Empty Search
        |--------------------------------------------------------------------------
        */

        if ($query === '') {
            return view('search.index', [
                'query' => $query,
                'type' => $type,
                'category' => $category,
                'duration' => $duration,
                'date' => $date,
                'sort' => $sort,
                'perPage' => $perPage,
                'videos' => collect(),
                'channels' => collect(),
                'categories' => $categories,
                'hasSearch' => false,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Video Search
        |--------------------------------------------------------------------------
        */

        $videos = collect();

        if ($type !== 'channels') {

            $videoQuery = Video::query()
                ->with([
                    'channel',
                    'category',
                ])
                ->where('status', 'published')
                ->where('visibility', 'public')
                ->where(function ($builder) use ($query) {

                    $builder
                        ->where('title', 'like', '%' . $query . '%')
                        ->orWhere(
                            'description',
                            'like',
                            '%' . $query . '%'
                        );
                });

            /*
            |--------------------------------------------------------------------------
            | Category Filter
            |--------------------------------------------------------------------------
            */

            if ($category) {

                $videoQuery->whereHas(
                    'category',
                    function ($builder) use ($category) {

                        $builder->where(
                            'slug',
                            $category
                        );
                    }
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Duration Filter
            |--------------------------------------------------------------------------
            |
            | short  = <= 4 minutes
            | medium = > 4 minutes and <= 20 minutes
            | long   = > 20 minutes
            |
            */

            if ($duration === 'short') {

                $videoQuery->where(
                    'duration',
                    '<=',
                    240
                );
            }

            if ($duration === 'medium') {

                $videoQuery
                    ->where(
                        'duration',
                        '>',
                        240
                    )
                    ->where(
                        'duration',
                        '<=',
                        1200
                    );
            }

            if ($duration === 'long') {

                $videoQuery->where(
                    'duration',
                    '>',
                    1200
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Upload Date Filter
            |--------------------------------------------------------------------------
            */

            if ($date !== 'all') {

                $dateLimit = match ($date) {

                    'today' => now()->subDay(),

                    'week' => now()->subDays(7),

                    'month' => now()->subDays(30),

                    'year' => now()->subYear(),

                    default => null,
                };

                if ($dateLimit) {

                    $videoQuery->where(
                        'published_at',
                        '>=',
                        $dateLimit
                    );
                }
            }

            /*
            |--------------------------------------------------------------------------
            | Sorting
            |--------------------------------------------------------------------------
            */

            if ($sort === 'latest') {

                $videoQuery->orderByDesc(
                    'published_at'
                );
            } elseif ($sort === 'views') {

                $videoQuery->orderByDesc(
                    'views_count'
                );
            } elseif ($sort === 'likes') {

                $videoQuery->orderByDesc(
                    'likes_count'
                );
            } else {

                /*
                |--------------------------------------------------------------------------
                | Relevance
                |--------------------------------------------------------------------------
                |
                | Exact title matches appear first,
                | then title matches,
                | then description matches.
                |
                */

                $searchLike = '%' . $query . '%';
                $exactLike = $query . '%';

                $videoQuery
                    ->orderByRaw(
                        'CASE
                            WHEN title = ? THEN 0
                            WHEN title LIKE ? THEN 1
                            WHEN description LIKE ? THEN 2
                            ELSE 3
                         END',
                        [
                            $query,
                            $exactLike,
                            $searchLike,
                        ]
                    )
                    ->orderByDesc(
                        'published_at'
                    );
            }

            $videos = $videoQuery
                ->paginate(
                    $perPage,
                    ['*'],
                    'videos_page'
                )
                ->withQueryString();
        }

        /*
        |--------------------------------------------------------------------------
        | Channel Search
        |--------------------------------------------------------------------------
        */

        $channels = collect();

        if ($type !== 'videos') {

            $channelQuery = Channel::query()
                ->with('user')
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
                });

            /*
            |--------------------------------------------------------------------------
            | Channel Sorting
            |--------------------------------------------------------------------------
            */

            $searchLike = '%' . $query . '%';

            $channelQuery
                ->orderByRaw(
                    'CASE
                        WHEN name = ? THEN 0
                        WHEN name LIKE ? THEN 1
                        WHEN handle LIKE ? THEN 2
                        ELSE 3
                     END',
                    [
                        $query,
                        $searchLike,
                        $searchLike,
                    ]
                )
                ->orderByDesc(
                    'subscriber_count'
                );

            $channels = $channelQuery
                ->take(12)
                ->get();
        }

        return view('search.index', [
            'query' => $query,
            'type' => $type,
            'category' => $category,
            'duration' => $duration,
            'date' => $date,
            'sort' => $sort,
            'perPage' => $perPage,
            'videos' => $videos,
            'channels' => $channels,
            'categories' => $categories,
            'hasSearch' => true,
        ]);
    }
}