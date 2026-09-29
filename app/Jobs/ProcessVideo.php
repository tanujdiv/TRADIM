<?php

namespace App\Jobs;

use App\Models\Video;
use App\Services\VideoProcessingService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Throwable;

class ProcessVideo implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public int $timeout = 3600;

    public array $backoff = [10, 30, 60];

    public function __construct(
        public int $videoId
    ) {
        $this->onConnection('redis_video');

        $this->onQueue(
            config(
                'tradim.queues.video',
                'processing'
            )
        );

        $this->afterCommit();
    }

    public function handle(
        VideoProcessingService $processingService
    ): void {
        $video = Video::query()->find($this->videoId);

        if (!$video) {
            Log::warning(
                'Video not found for processing',
                [
                    'video_id' => $this->videoId,
                    'attempt' => $this->attempts(),
                ]
            );

            return;
        }

        if ($video->status === 'ready') {
            Log::info(
                'Video processing skipped because video is already ready',
                [
                    'video_id' => $video->id,
                ]
            );

            return;
        }

        if ($video->status === 'published') {
            Log::info(
                'Video processing skipped because video is already published',
                [
                    'video_id' => $video->id,
                ]
            );

            return;
        }

        Log::info(
            'Video processing job started',
            [
                'video_id' => $video->id,
                'attempt' => $this->attempts(),
                'queue' => $this->queue,
                'connection' => $this->connection,
            ]
        );

        try {
            $processingService->process($video);

            Log::info(
                'Video processing job completed',
                [
                    'video_id' => $video->id,
                    'attempt' => $this->attempts(),
                ]
            );
        } catch (Throwable $exception) {
            Log::error(
                'Video processing attempt failed',
                [
                    'video_id' => $video->id,
                    'attempt' => $this->attempts(),
                    'max_attempts' => $this->tries,
                    'message' => $exception->getMessage(),
                    'file' => $exception->getFile(),
                    'line' => $exception->getLine(),
                ]
            );

            throw $exception;
        }
    }

    public function failed(
        Throwable $exception
    ): void {
        Video::query()
            ->whereKey($this->videoId)
            ->update([
                'status' => 'failed',
            ]);

        Log::error(
            'Video processing permanently failed',
            [
                'video_id' => $this->videoId,
                'attempts' => $this->tries,
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
            ]
        );
    }
}