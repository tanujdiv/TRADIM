<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Channel;
use Illuminate\Http\JsonResponse;

class ChannelController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Show Channel
    |--------------------------------------------------------------------------
    */

    public function show(
        Channel $channel
    ): JsonResponse {

        $channel->load('user');


        $videos = $channel
            ->videos()
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
            ->whereNotNull(
                'published_at'
            )
            ->latest('published_at')
            ->paginate(12);


        return response()->json([

            'success' => true,

            'data' => [

                'channel' =>
                    $channel,

                'videos' =>
                    $videos,

            ],

        ]);
    }
}