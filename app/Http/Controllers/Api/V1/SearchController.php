<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Channel;
use App\Models\Video;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Advanced Search API
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $query = trim(
            (string) $request->input('q', '')
        );

        if ($query === '') {

            return response()->json([
                'success' => true,
                'message' => 'Search query is required.',
                'data' => [
                    'query' => '',
                    'videos' => [],
                    'channels' => [],
                ],
            ], 422);
        }

        $type = $request->input('type', 'all');

        if (
            !in_array($type, [
                'all',
                'videos',
                'channels',
            ], true)
        ) {
            $type = 'all';
        }

        $category = $request->input('category');

        $duration = $request->input(
            'duration',
            'all'
        );

        $date = $request->input(
            'date',
            'all'
        );

        $sort = $request->input(
            'sort',
            'relevance'
        );

        $perPage = (int) $request->input(
            'per_page',
            12
        );

        if (
            !in_array($perPage, [
                12,
                24,
                48,
            ], true)
        ) {
            $perPage = 12;
        }

        /*
        |--------------------------------------------------------------------------
        | Videos
        |--------------------------------------------------------------------------
        */

        $videos = null;

        if ($type !== 'channels') {

            $videoQuery = Video::query()
                ->with([
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
                });

            /*
            |--------------------------------------------------------------------------
            | Category
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
            | Duration
            |--------------------------------------------------------------------------
            */

            if ($duration === 'short') {

                $videoQuery->where(
                    'duration',
                    '<=',
                    240
                );
            } elseif ($duration === 'medium') {

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
            } elseif ($duration === 'long') {

                $videoQuery->where(
                    'duration',
                    '>',
                    1200
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Date
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
                            $query . '%',
                            '%' . $query . '%',
                        ]
                    )
                    ->orderByDesc(
                        'published_at'
                    );
            }

            $videos = $videoQuery
                ->paginate($perPage)
                ->withQueryString();
        }

        /*
        |--------------------------------------------------------------------------
        | Channels
        |--------------------------------------------------------------------------
        */

        $channels = [];

        if ($type !== 'videos') {

            $channels = Channel::query()
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
                })
                ->orderByRaw(
                    'CASE
                        WHEN name = ? THEN 0
                        WHEN name LIKE ? THEN 1
                        WHEN handle LIKE ? THEN 2
                        ELSE 3
                     END',
                    [
                        $query,
                        '%' . $query . '%',
                        '%' . $query . '%',
                    ]
                )
                ->orderByDesc(
                    'subscriber_count'
                )
                ->take(12)
                ->get();
        }

        /*
        |--------------------------------------------------------------------------
        | Response
        |--------------------------------------------------------------------------
        */

        return response()->json([
            'success' => true,

            'data' => [
                'query' => $query,

                'filters' => [
                    'type' => $type,
                    'category' => $category,
                    'duration' => $duration,
                    'date' => $date,
                    'sort' => $sort,
                    'per_page' => $perPage,
                ],

                'videos' => $videos,

                'channels' => $channels,
            ],
        ]);
    }
}