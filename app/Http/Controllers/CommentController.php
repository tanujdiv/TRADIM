<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Notification;
use App\Models\Video;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Store Comment
    |--------------------------------------------------------------------------
    */

    public function store(Request $request, Video $video): RedirectResponse
    {
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
        | Create Comment
        |--------------------------------------------------------------------------
        */

        Comment::create([
            'user_id' => $user->id,
            'video_id' => $video->id,
            'parent_id' => $validated['parent_id'] ?? null,
            'body' => $validated['comment'],
            'comment' => $validated['comment'], // Database column constraint compatibility
        ]);

        /*
        |--------------------------------------------------------------------------
        | Notify Channel Owner
        |--------------------------------------------------------------------------
        */

        $channelOwnerId = $video->channel?->user_id;

        if ($channelOwnerId && $channelOwnerId !== $user->id) {
            Notification::create([
                'user_id' => $channelOwnerId,
                'type' => 'video_comment',
                'title' => 'New comment',
                'message' => $user->name . ' commented on your video "' . $video->title . '"',
                'url' => route('videos.show', $video->slug),
                'actor_id' => $user->id,
                'is_read' => false,
                'read_at' => null,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Update Comment Count (Top-level comments only)
        |--------------------------------------------------------------------------
        */

        $video->update([
            'comments_count' => $video->comments()
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

    public function destroy(Comment $comment): RedirectResponse
    {
        $userId = Auth::id();

        /*
        |--------------------------------------------------------------------------
        | Only Comment Owner Can Delete
        |--------------------------------------------------------------------------
        */

        if ($comment->user_id !== $userId) {
            abort(403);
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
                'comments_count' => $video->comments()
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