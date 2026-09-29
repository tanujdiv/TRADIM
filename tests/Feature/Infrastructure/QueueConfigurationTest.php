<?php

namespace Tests\Feature\Infrastructure;

use Tests\TestCase;

class QueueConfigurationTest extends TestCase
{
    public function test_video_queue_configuration_exists(): void
    {
        $this->assertNotEmpty(
            config('tradim.queues.video')
        );
    }

    public function test_notification_queue_configuration_exists(): void
    {
        $this->assertNotEmpty(
            config('tradim.queues.notifications')
        );
    }

    public function test_dedicated_video_queue_uses_redis(): void
    {
        $this->assertSame(
            'redis',
            config('queue.connections.redis_video.driver')
        );
    }

    public function test_video_retry_after_exceeds_job_timeout(): void
    {
        $retryAfter = config(
            'queue.connections.redis_video.retry_after'
        );

        $jobTimeout = (new \App\Jobs\ProcessVideo(999))->timeout;

        $this->assertGreaterThan(
            $jobTimeout,
            $retryAfter
        );
    }

    public function test_video_queue_dispatches_after_commit(): void
    {
        $this->assertTrue(
            config('queue.connections.redis_video.after_commit')
        );
    }

    public function test_testing_uses_synchronous_default_queue(): void
    {
        $this->assertSame(
            'sync',
            config('queue.default')
        );
    }
}