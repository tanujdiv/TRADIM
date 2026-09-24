<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->enum('target_type', [
                'video',
                'channel',
                'comment',
            ]);

            $table->unsignedBigInteger('target_id');

            $table->string('target_title', 255);

            $table->enum('reason', [
                'spam',
                'harassment',
                'hate_speech',
                'violence',
                'sexual_content',
                'misinformation',
                'copyright',
                'other',
            ]);

            $table->text('description')->nullable();

            $table->enum('status', [
                'pending',
                'reviewing',
                'resolved',
                'rejected',
            ])->default('pending');

            $table->foreignId('reviewed_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->text('admin_note')->nullable();

            $table->enum('action_taken', [
                'none',
                'content_removed',
            ])->default('none');

            $table->timestamp('reviewed_at')->nullable();

            $table->timestamps();

            $table->index([
                'target_type',
                'target_id',
            ]);

            $table->index([
                'status',
                'created_at',
            ]);

            $table->index([
                'user_id',
                'status',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};