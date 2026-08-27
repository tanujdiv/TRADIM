<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use App\Models\Video;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Search
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $query = trim(
            $request->input('q', '')
        );


        /*
        |--------------------------------------------------------------------------
        | Empty Search
        |--------------------------------------------------------------------------
        */

        if ($query === '') {

            return view(
                'search.index',
                [
                    'query' => $query,
                    'videos' => collect(),
                    'channels' => collect(),
                ]
            );
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
            ->paginate(
                12,
                ['*'],
                'videos_page'
            );


        /*
        |--------------------------------------------------------------------------
        | Search Channels
        |--------------------------------------------------------------------------
        */

        $channels = Channel::with('user')
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
            ->latest()
            ->take(12)
            ->get();


        return view(
            'search.index',
            compact(
                'query',
                'videos',
                'channels'
            )
        );
    }
}