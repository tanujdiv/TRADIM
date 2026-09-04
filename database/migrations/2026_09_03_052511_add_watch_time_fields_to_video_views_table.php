<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('video_views', function (Blueprint $table) {
            if (!Schema::hasColumn('video_views', 'watch_seconds')) {
                $table->unsignedInteger('watch_seconds')
                    ->default(0)
                    ->after('video_id');
            }

            if (!Schema::hasColumn('video_views', 'last_position')) {
                $table->unsignedInteger('last_position')
                    ->default(0)
                    ->after('watch_seconds');
            }

            if (!Schema::hasColumn('video_views', 'completed')) {
                $table->boolean('completed')
                    ->default(false)
                    ->after('last_position');
            }

            if (!Schema::hasColumn('video_views', 'user_id')) {
                $table->foreignId('user_id')
                    ->nullable()
                    ->after('video_id')
                    ->constrained()
                    ->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('video_views', function (Blueprint $table) {
            if (Schema::hasColumn('video_views', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            }

            if (Schema::hasColumn('video_views', 'completed')) {
                $table->dropColumn('completed');
            }

            if (Schema::hasColumn('video_views', 'last_position')) {
                $table->dropColumn('last_position');
            }

            if (Schema::hasColumn('video_views', 'watch_seconds')) {
                $table->dropColumn('watch_seconds');
            }
        });
    }
};