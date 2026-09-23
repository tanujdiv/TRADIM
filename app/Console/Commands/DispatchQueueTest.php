<?php

namespace App\Console\Commands;

use App\Jobs\ProcessQueueTest;
use Illuminate\Console\Command;

class DispatchQueueTest extends Command
{
    protected $signature = 'tradim:queue-test
                            {message=Redis queue is working}';

    protected $description = 'Dispatch a test job to the Redis queue';

    public function handle(): int
    {
        $message = (string) $this->argument('message');

        ProcessQueueTest::dispatch($message);

        $this->info('Queue job dispatched successfully.');

        return self::SUCCESS;
    }
}