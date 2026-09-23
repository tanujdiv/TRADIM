<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Throwable;

class ProcessQueueTest implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public int $backoff = 5;

    public function __construct(
        public string $message
    ) {
        $this->onQueue('default');
    }

    public function handle(): void
    {
        Log::info('Queue job processed successfully.', [
            'message' => $this->message,
            'job' => self::class,
            'queue' => 'default',
        ]);
    }

    public function failed(Throwable $exception): void
    {
        Log::error('Queue job processing failed.', [
            'message' => $this->message,
            'job' => self::class,
            'queue' => 'default',
            'error' => $exception->getMessage(),
        ]);
    }
}