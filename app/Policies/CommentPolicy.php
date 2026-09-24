<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;
use App\Models\Video;

class CommentPolicy
{
    public function viewAny(?User $user): bool
    {
        return true;
    }

    public function create(User $user, Video $video): bool
    {
        return $user->is_active
            && $user->can('view', $video)
            && $video->status === 'published';
    }

    public function delete(User $user, Comment $comment): bool
    {
        return $user->is_active
            && $comment->user_id === $user->id;
    }
}