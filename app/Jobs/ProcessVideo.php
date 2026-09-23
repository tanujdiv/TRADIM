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

    public array $backoff = [
        10,
        30,
        60,
    ];

    public function __construct(
        public int $videoId
    ) {
        $this->onQueue('videos');
    }

    public function handle(
        VideoProcessingService $processingService
    ): void {
        $video = Video::query()
            ->find($this->videoId);

        if (!$video) {
            Log::warning(
                'STEP 24: Video not found for processing',
                [
                    'video_id' => $this->videoId,
                ]
            );

            return;
        }

        Log::info(
            'STEP 24: Video processing job started',
            [
                'video_id' => $video->id,
            ]
        );

        try {
            $processingService->process($video);
        } catch (Throwable $exception) {
            $video->update([
                'status' => 'failed',
            ]);

            Log::error(
                'STEP 24: Video processing failed',
                [
                    'video_id' => $video->id,
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
            'STEP 24: Video processing permanently failed',
            [
                'video_id' => $this->videoId,
                'message' => $exception->getMessage(),
            ]
        );
    }
}