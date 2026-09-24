<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CommentController extends Controller
{
    public function index(
        Request $request
    ): View {
        $search = trim(
            (string) $request->input('search')
        );

        $comments = Comment::query()
            ->with([
                'user',
                'video',
            ])
            ->when(
                $search !== '',
                function ($query) use ($search) {
                    $query->where(
                        'comment',
                        'like',
                        "%{$search}%"
                    );
                }
            )
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view(
            'admin.comments.index',
            compact('comments', 'search')
        );
    }

    public function destroy(
        Comment $comment
    ): RedirectResponse {
        $video = $comment->video;

        $comment->delete();

        if ($video) {
            $video->update([
                'comments_count' => $video
                    ->comments()
                    ->whereNull('parent_id')
                    ->count(),
            ]);
        }

        return back()->with(
            'success',
            'Comment deleted successfully.'
        );
    }
}