<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Notification;
use App\Models\Video;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;

class CommentController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Store Comment
    |--------------------------------------------------------------------------
    */

    public function store(
        Request $request,
        Video $video
    ): RedirectResponse {

        Gate::authorize(
            'create',
            [Comment::class, $video]
        );

        $user = Auth::user();

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

        if (!empty($validated['parent_id'])) {

            $parentIsValid = Comment::query()
                ->whereKey($validated['parent_id'])
                ->where('video_id', $video->id)
                ->exists();

            if (!$parentIsValid) {
                throw ValidationException::withMessages([
                    'parent_id' => 'Invalid parent comment.',
                ]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Create Comment
        |--------------------------------------------------------------------------
        */

        Comment::create([

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

        $channelOwnerId = $video->channel?->user_id;

        if (
            $channelOwnerId &&
            $channelOwnerId !== $user->id
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
                    route('videos.show', $video->slug),

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
                $video->comments()
                    ->whereNull('parent_id')
                    ->count(),

        ]);

        return back()->with(
            'success',
            'Comment added successfully.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Delete Comment
    |--------------------------------------------------------------------------
    */

    public function destroy(
        Comment $comment
    ): RedirectResponse {

        Gate::authorize('delete', $comment);

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
                    $video->comments()
                        ->whereNull('parent_id')
                        ->count(),

            ]);
        }

        return back()->with(
            'success',
            'Comment deleted.'
        );
    }
}