<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Notification;
use App\Models\Video;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Comments List
    |--------------------------------------------------------------------------
    */

    public function index(
        Video $video
    ): JsonResponse {

        $comments = $video
            ->comments()
            ->with([
                'user',
                'replies.user',
            ])
            ->whereNull(
                'parent_id'
            )
            ->latest()
            ->paginate(20);


        return response()->json([

            'success' => true,

            'data' => $comments,

        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Store Comment
    |--------------------------------------------------------------------------
    */

    public function store(
        Request $request,
        Video $video
    ): JsonResponse {

        $user = $request->user();


        $validated = $request->validate([

            'comment' => [
                'required',
                'string',
                'max:2000',
            ],

            'parent_id' => [
                'nullable',
                'exists:comments,id',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Validate Parent Comment
        |--------------------------------------------------------------------------
        */

        if (
            !empty($validated['parent_id'])
        ) {

            $parentComment =
                Comment::find(
                    $validated['parent_id']
                );


            if (
                !$parentComment ||
                $parentComment->video_id !==
                    $video->id
            ) {

                return response()->json([

                    'success' => false,

                    'message' =>
                        'Invalid parent comment.',

                ], 422);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Create Comment
        |--------------------------------------------------------------------------
        */

        $comment = Comment::create([

            'user_id' =>
                $user->id,

            'video_id' =>
                $video->id,

            'parent_id' =>
                $validated['parent_id'] ?? null,

            'body' =>
                $validated['comment'],

            'comment' =>
                $validated['comment'],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Notify Channel Owner
        |--------------------------------------------------------------------------
        */

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
                    'video_comment',

                'title' =>
                    'New comment',

                'message' =>
                    $user->name .
                    ' commented on your video "' .
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


        /*
        |--------------------------------------------------------------------------
        | Update Comment Count
        |--------------------------------------------------------------------------
        */

        $video->update([

            'comments_count' =>
                $video
                    ->comments()
                    ->whereNull(
                        'parent_id'
                    )
                    ->count(),

        ]);


        $comment->load('user');


        return response()->json([

            'success' => true,

            'message' =>
                'Comment added successfully.',

            'data' => [

                'comment' =>
                    $comment,

                'comments_count' =>
                    (int) $video
                        ->fresh()
                        ->comments_count,

            ],

        ], 201);
    }


    /*
    |--------------------------------------------------------------------------
    | Delete Comment
    |--------------------------------------------------------------------------
    */

    public function destroy(
        Request $request,
        Comment $comment
    ): JsonResponse {

        $user = $request->user();


        /*
        |--------------------------------------------------------------------------
        | Comment Owner
        |--------------------------------------------------------------------------
        */

        if (
            $comment->user_id !==
            $user->id
        ) {

            return response()->json([

                'success' => false,

                'message' =>
                    'You are not allowed to delete this comment.',

            ], 403);
        }


        $video = $comment->video;


        $comment->delete();


        /*
        |--------------------------------------------------------------------------
        | Update Comment Count
        |--------------------------------------------------------------------------
        */

        if ($video) {

            $video->update([

                'comments_count' =>
                    $video
                        ->comments()
                        ->whereNull(
                            'parent_id'
                        )
                        ->count(),

            ]);
        }


        return response()->json([

            'success' => true,

            'message' =>
                'Comment deleted successfully.',

        ]);
    }
}