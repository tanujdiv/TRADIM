<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('channels', function (Blueprint $table) {

            $table->id();

            $table->foreignId('user_id')
                ->unique()
                ->constrained('users')
                ->cascadeOnDelete();

            $table->string('name', 100);

            $table->string('slug', 120)
                ->unique();

            $table->string('handle', 50)
                ->unique();

            $table->text('description')
                ->nullable();

            $table->string('avatar')
                ->nullable();

            $table->string('banner')
                ->nullable();

            $table->unsignedBigInteger('subscriber_count')
                ->default(0);

            $table->unsignedBigInteger('video_count')
                ->default(0);

            $table->unsignedBigInteger('total_views')
                ->default(0);

            $table->boolean('is_verified')
                ->default(false);

            $table->boolean('is_active')
                ->default(true);

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('channels');
    }
};