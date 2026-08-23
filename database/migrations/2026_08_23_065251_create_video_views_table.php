<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('video_views', function (Blueprint $table) {

            $table->id();

            $table->foreignId('video_id')
                ->constrained('videos')
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('session_id')
                ->nullable();

            $table->string('ip_hash', 64)
                ->nullable();

            $table->unsignedInteger('watched_seconds')
                ->default(0);

            $table->timestamp('created_at')
                ->useCurrent();

            $table->index([
                'video_id',
                'created_at'
            ]);

            $table->index('user_id');

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('video_views');
    }
};