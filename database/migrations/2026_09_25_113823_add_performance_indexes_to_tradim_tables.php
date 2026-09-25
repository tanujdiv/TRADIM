<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('videos', function (Blueprint $table) {
            $table->index(
                ['status', 'visibility', 'published_at'],
                'videos_public_search_idx'
            );

            $table->index(
                ['status', 'visibility', 'views_count'],
                'videos_popular_search_idx'
            );

            $table->index(
                ['status', 'visibility', 'likes_count'],
                'videos_liked_search_idx'
            );

            $table->index(
                ['category_id', 'status', 'visibility'],
                'videos_category_search_idx'
            );
        });

        Schema::table('channels', function (Blueprint $table) {
            $table->index(
                ['subscriber_count', 'created_at'],
                'channels_discovery_idx'
            );
        });

        Schema::table('comments', function (Blueprint $table) {
            $table->index(
                ['video_id', 'created_at'],
                'comments_video_recent_idx'
            );
        });

        Schema::table('video_views', function (Blueprint $table) {
            $table->index(
                ['video_id', 'last_viewed_at'],
                'video_views_recent_idx'
            );
        });
    }

    public function down(): void
    {
        Schema::table('video_views', function (Blueprint $table) {
            $table->dropIndex('video_views_recent_idx');
        });

        Schema::table('comments', function (Blueprint $table) {
            $table->dropIndex('comments_video_recent_idx');
        });

        Schema::table('channels', function (Blueprint $table) {
            $table->dropIndex('channels_discovery_idx');
        });

        Schema::table('videos', function (Blueprint $table) {
            $table->dropIndex('videos_category_search_idx');
            $table->dropIndex('videos_liked_search_idx');
            $table->dropIndex('videos_popular_search_idx');
            $table->dropIndex('videos_public_search_idx');
        });
    }
};