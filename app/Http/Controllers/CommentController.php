<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
    ) {
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
            'comment' => $validated['comment'], // Populate both columns to fix DB constraint
        ]);


        /*
        |--------------------------------------------------------------------------
        | Update Comment Count
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

    public function destroy(
        Comment $comment
    ) {
        $user = Auth::user();


        /*
        |--------------------------------------------------------------------------
        | Only Comment Owner Can Delete
        |--------------------------------------------------------------------------
        */

        if ($comment->user_id !== $user->id) {
            abort(403);
        }


        $video = $comment->video;


        $comment->delete();


        /*
        |--------------------------------------------------------------------------
        | Update Comment Count
        |--------------------------------------------------------------------------
        */

        $video->update([
            'comments_count' => $video->comments()
                ->whereNull('parent_id')
                ->count(),
        ]);


        return back()->with(
            'success',
            'Comment deleted.'
        );
    }
}