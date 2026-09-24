<?php

namespace App\Policies;

use App\Models\Channel;
use App\Models\User;

class ChannelPolicy
{
    public function viewAny(?User $user): bool
    {
        return true;
    }

    public function view(?User $user, Channel $channel): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->is_active
            && !$user->channel()->exists();
    }

    public function update(User $user, Channel $channel): bool
    {
        return $user->is_active
            && $channel->user_id === $user->id;
    }

    public function delete(User $user, Channel $channel): bool
    {
        return false;
    }
}