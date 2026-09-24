<?php

namespace Tests\Feature\Security;

use App\Models\Channel;
use App\Models\Comment;
use App\Models\User;
use App\Models\Video;
use App\Policies\ChannelPolicy;
use App\Policies\CommentPolicy;
use App\Policies\VideoPolicy;
use Tests\TestCase;

class AuthorizationTest extends TestCase
{
    public function test_user_cannot_update_another_users_channel(): void
    {
        $owner = User::factory()->make([
            'id' => 1,
            'is_active' => true,
        ]);

        $otherUser = User::factory()->make([
            'id' => 2,
            'is_active' => true,
        ]);

        $channel = new Channel([
            'user_id' => $owner->id,
            'name' => 'Owner Channel',
            'handle' => 'owner-channel',
        ]);

        $policy = new ChannelPolicy();

        $this->assertFalse(
            $policy->update(
                $otherUser,
                $channel
            )
        );

        $this->assertTrue(
            $policy->update(
                $owner,
                $channel
            )
        );
    }

    public function test_user_cannot_delete_another_users_comment(): void
    {
        $owner = User::factory()->make([
            'id' => 1,
            'is_active' => true,
        ]);

        $otherUser = User::factory()->make([
            'id' => 2,
            'is_active' => true,
        ]);

        $comment = new Comment([
            'user_id' => $owner->id,
            'comment' => 'Test comment',
            'body' => 'Test comment',
        ]);

        $policy = new CommentPolicy();

        $this->assertFalse(
            $policy->delete(
                $otherUser,
                $comment
            )
        );

        $this->assertTrue(
            $policy->delete(
                $owner,
                $comment
            )
        );
    }

    public function test_private_video_is_not_publicly_accessible(): void
    {
        $owner = User::factory()->make([
            'id' => 1,
            'is_active' => true,
        ]);

        $video = new Video([
            'user_id' => $owner->id,
            'visibility' => 'private',
            'status' => 'published',
        ]);

        $policy = new VideoPolicy();

        $this->assertFalse(
            $policy->view(
                null,
                $video
            )
        );

        $this->assertTrue(
            $policy->view(
                $owner,
                $video
            )
        );
    }
}