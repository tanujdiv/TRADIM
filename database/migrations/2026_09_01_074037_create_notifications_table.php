<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {

            $table->id();

            // User who receives the notification
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // Notification type
            $table->string('type', 50);

            // Main notification message
            $table->string('title');

            // Optional detailed message
            $table->text('message')->nullable();

            // Optional related data
            $table->string('url')->nullable();

            // Optional actor
            $table->foreignId('actor_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // Read status
            $table->boolean('is_read')
                ->default(false);

            // When notification was read
            $table->timestamp('read_at')
                ->nullable();

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | Indexes
            |--------------------------------------------------------------------------
            */

            $table->index([
                'user_id',
                'is_read',
            ]);

            $table->index([
                'user_id',
                'created_at',
            ]);

            $table->index('actor_id');
            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};