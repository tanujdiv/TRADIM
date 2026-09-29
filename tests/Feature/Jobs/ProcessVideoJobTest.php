<?php

namespace Tests\Feature\Jobs;

use App\Jobs\ProcessVideo;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class ProcessVideoJobTest extends TestCase
{
    public function test_video_job_uses_dedicated_redis_connection(): void
    {
        $job = new ProcessVideo(999);

        $this->assertSame(
            'redis_video',
            $job->connection
        );
    }

    public function test_video_job_uses_configured_queue(): void
    {
        $job = new ProcessVideo(999);

        $this->assertSame(
            config('tradim.queues.video'),
            $job->queue
        );
    }

    public function test_video_job_has_correct_retry_settings(): void
    {
        $job = new ProcessVideo(999);

        $this->assertSame(3, $job->tries);
        $this->assertSame(3600, $job->timeout);
        $this->assertSame([10, 30, 60], $job->backoff);
    }

    public function test_video_job_can_be_dispatched(): void
    {
        Queue::fake();

        ProcessVideo::dispatch(999);

        Queue::assertPushed(
            ProcessVideo::class,
            function (ProcessVideo $job) {
                return $job->videoId === 999
                    && $job->connection === 'redis_video'
                    && $job->queue === config('tradim.queues.video');
            }
        );
    }
}