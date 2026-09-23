<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Like;
use App\Models\Notification;
use App\Models\Video;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Create / Change Like
    |--------------------------------------------------------------------------
    */

    public function store(
        Request $request,
        Video $video
    ): JsonResponse {

        $user = $request->user();


        $type = $request->input(
            'type',
            'like'
        );


        if (
            !in_array(
                $type,
                ['like', 'dislike'],
                true
            )
        ) {

            return response()->json([

                'success' => false,

                'message' =>
                    'Invalid reaction type.',

            ], 422);
        }


        $existingLike = Like::where(
            'user_id',
            $user->id
        )
            ->where(
                'video_id',
                $video->id
            )
            ->first();


        $shouldNotify = false;


        /*
        |--------------------------------------------------------------------------
        | Remove Same Reaction
        |--------------------------------------------------------------------------
        */

        if (
            $existingLike &&
            $existingLike->type === $type
        ) {

            $existingLike->delete();

        }


        /*
        |--------------------------------------------------------------------------
        | Change Reaction
        |--------------------------------------------------------------------------
        */

        elseif ($existingLike) {

            $oldType =
                $existingLike->type;

            $existingLike->update([

                'type' =>
                    $type,

            ]);


            if (
                $oldType !== 'like' &&
                $type === 'like'
            ) {

                $shouldNotify = true;
            }
        }


        /*
        |--------------------------------------------------------------------------
        | New Reaction
        |--------------------------------------------------------------------------
        */

        else {

            Like::create([

                'user_id' =>
                    $user->id,

                'video_id' =>
                    $video->id,

                'type' =>
                    $type,

            ]);


            if ($type === 'like') {

                $shouldNotify = true;
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Update Counts
        |--------------------------------------------------------------------------
        */

        $video->update([

            'likes_count' =>
                $video
                    ->likes()
                    ->where(
                        'type',
                        'like'
                    )
                    ->count(),

            'dislikes_count' =>
                $video
                    ->likes()
                    ->where(
                        'type',
                        'dislike'
                    )
                    ->count(),

        ]);


        /*
        |--------------------------------------------------------------------------
        | Like Notification
        |--------------------------------------------------------------------------
        */

        if ($shouldNotify) {

            $channelOwnerId =
                $video->channel?->user_id;


            if (
                $channelOwnerId &&
                $channelOwnerId !==
                    $user->id
            ) {

                Notification::create([

                    'user_id' =>
                        $channelOwnerId,

                    'type' =>
                        'video_like',

                    'title' =>
                        'New like',

                    'message' =>
                        $user->name .
                        ' liked your video "' .
                        $video->title .
                        '"',

                    'url' =>
                        route(
                            'videos.show',
                            $video->slug
                        ),

                    'actor_id' =>
                        $user->id,

                    'is_read' =>
                        false,

                    'read_at' =>
                        null,

                ]);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Current Reaction
        |--------------------------------------------------------------------------
        */

        $currentReaction = Like::where(
            'user_id',
            $user->id
        )
            ->where(
                'video_id',
                $video->id
            )
            ->value('type');


        return response()->json([

            'success' => true,

            'message' =>
                'Reaction updated successfully.',

            'data' => [

                'reaction' =>
                    $currentReaction,

                'likes_count' =>
                    (int) $video
                        ->fresh()
                        ->likes_count,

                'dislikes_count' =>
                    (int) $video
                        ->fresh()
                        ->dislikes_count,

            ],

        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Remove Reaction
    |--------------------------------------------------------------------------
    */

    public function destroy(
        Request $request,
        Video $video
    ): JsonResponse {

        $user = $request->user();


        Like::where(
            'user_id',
            $user->id
        )
            ->where(
                'video_id',
                $video->id
            )
            ->delete();


        $video->update([

            'likes_count' =>
                $video
                    ->likes()
                    ->where(
                        'type',
                        'like'
                    )
                    ->count(),

            'dislikes_count' =>
                $video
                    ->likes()
                    ->where(
                        'type',
                        'dislike'
                    )
                    ->count(),

        ]);


        $video->refresh();


        return response()->json([

            'success' => true,

            'message' =>
                'Reaction removed successfully.',

            'data' => [

                'reaction' =>
                    null,

                'likes_count' =>
                    (int) $video->likes_count,

                'dislikes_count' =>
                    (int) $video->dislikes_count,

            ],

        ]);
    }
}