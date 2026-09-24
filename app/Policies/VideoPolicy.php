<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Video;

class VideoPolicy
{
    public function viewAny(?User $user): bool
    {
        return true;
    }

    public function view(?User $user, Video $video): bool
    {
        if ($user && $user->id === $video->user_id) {
            return $user->is_active;
        }

        return $video->status === 'published'
            && in_array(
                $video->visibility,
                ['public', 'unlisted'],
                true
            );
    }

    public function create(User $user): bool
    {
        return $user->is_active
            && $user->channel()->exists();
    }

    public function update(User $user, Video $video): bool
    {
        return $user->is_active
            && $user->id === $video->user_id
            && $user->channel()
                ->whereKey($video->channel_id)
                ->exists();
    }

    public function delete(User $user, Video $video): bool
    {
        return $this->update($user, $video);
    }
}