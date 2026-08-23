<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('videos', function (Blueprint $table) {

            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('channel_id')
                ->constrained('channels')
                ->cascadeOnDelete();

            $table->foreignId('category_id')
                ->nullable()
                ->constrained('categories')
                ->nullOnDelete();

            $table->string('title', 255);

            $table->string('slug', 300)
                ->unique();

            $table->text('description')
                ->nullable();

            $table->string('video_path')
                ->nullable();

            $table->string('thumbnail_path')
                ->nullable();

            $table->unsignedInteger('duration')
                ->default(0);

            $table->enum('visibility', [
                'public',
                'unlisted',
                'private'
            ])->default('public');

            $table->enum('status', [
                'draft',
                'processing',
                'published',
                'failed'
            ])->default('draft');

            $table->unsignedBigInteger('views_count')
                ->default(0);

            $table->unsignedBigInteger('likes_count')
                ->default(0);

            $table->unsignedBigInteger('dislikes_count')
                ->default(0);

            $table->unsignedBigInteger('comments_count')
                ->default(0);

            $table->timestamp('published_at')
                ->nullable();

            $table->timestamps();

            $table->index('status');
            $table->index('visibility');
            $table->index('published_at');
            $table->index('category_id');

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('videos');
    }
};