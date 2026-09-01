<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        /*
        |--------------------------------------------------------------------------
        | USERS
        |--------------------------------------------------------------------------
        */

        Schema::table('users', function (Blueprint $table) {
            // Email is normally already indexed/unique.
            // No duplicate index added here.
        });


        /*
        |--------------------------------------------------------------------------
        | CHANNELS
        |--------------------------------------------------------------------------
        */

        Schema::table('channels', function (Blueprint $table) {

            // Fast channel lookup by handle
            if (!$this->indexExists('channels', 'channels_handle_index')) {
                $table->index('handle');
            }

            // Fast lookup of user's channel
            if (!$this->indexExists('channels', 'channels_user_id_index')) {
                $table->index('user_id');
            }

            // Useful for popular channel sorting
            if (!$this->indexExists('channels', 'channels_subscriber_count_index')) {
                $table->index('subscriber_count');
            }
        });


        /*
        |--------------------------------------------------------------------------
        | VIDEOS
        |--------------------------------------------------------------------------
        */

        Schema::table('videos', function (Blueprint $table) {

            // Slug lookup
            if (!$this->indexExists('videos', 'videos_slug_index')) {
                $table->index('slug');
            }

            // Channel videos
            if (!$this->indexExists('videos', 'videos_channel_id_index')) {
                $table->index('channel_id');
            }

            // Category filtering
            if (!$this->indexExists('videos', 'videos_category_id_index')) {
                $table->index('category_id');
            }

            // Publishing/status filtering
            if (!$this->indexExists('videos', 'videos_status_index')) {
                $table->index('status');
            }

            // Visibility filtering
            if (!$this->indexExists('videos', 'videos_visibility_index')) {
                $table->index('visibility');
            }

            // Newest videos
            if (!$this->indexExists('videos', 'videos_published_at_index')) {
                $table->index('published_at');
            }

            // Common channel video query
            if (!$this->indexExists('videos', 'videos_channel_status_visibility_index')) {
                $table->index([
                    'channel_id',
                    'status',
                    'visibility',
                ]);
            }
        });


        /*
        |--------------------------------------------------------------------------
        | VIDEO VIEWS
        |--------------------------------------------------------------------------
        */

        Schema::table('video_views', function (Blueprint $table) {

            if (!$this->indexExists('video_views', 'video_views_video_id_index')) {
                $table->index('video_id');
            }

            if (!$this->indexExists('video_views', 'video_views_user_id_index')) {
                $table->index('user_id');
            }
        });


        /*
        |--------------------------------------------------------------------------
        | LIKES
        |--------------------------------------------------------------------------
        */

        Schema::table('likes', function (Blueprint $table) {

            if (!$this->indexExists('likes', 'likes_video_id_index')) {
                $table->index('video_id');
            }

            if (!$this->indexExists('likes', 'likes_user_id_index')) {
                $table->index('user_id');
            }
        });


        /*
        |--------------------------------------------------------------------------
        | COMMENTS
        |--------------------------------------------------------------------------
        */

        Schema::table('comments', function (Blueprint $table) {

            if (!$this->indexExists('comments', 'comments_video_id_index')) {
                $table->index('video_id');
            }

            if (!$this->indexExists('comments', 'comments_user_id_index')) {
                $table->index('user_id');
            }

            if (!$this->indexExists('comments', 'comments_parent_id_index')) {
                $table->index('parent_id');
            }
        });


        /*
        |--------------------------------------------------------------------------
        | SUBSCRIPTIONS
        |--------------------------------------------------------------------------
        */

        Schema::table('subscriptions', function (Blueprint $table) {

            if (!$this->indexExists('subscriptions', 'subscriptions_user_id_index')) {
                $table->index('user_id');
            }

            if (!$this->indexExists('subscriptions', 'subscriptions_channel_id_index')) {
                $table->index('channel_id');
            }

            /*
             * A user should subscribe to a channel only once.
             */
            if (
                !$this->indexExists(
                    'subscriptions',
                    'subscriptions_user_id_channel_id_unique'
                )
            ) {
                $table->unique([
                    'user_id',
                    'channel_id',
                ]);
            }
        });
    }


    public function down(): void
    {
        /*
        |--------------------------------------------------------------------------
        | CHANNELS
        |--------------------------------------------------------------------------
        */

        Schema::table('channels', function (Blueprint $table) {
            $this->dropIndexIfExists($table, 'channels_handle_index');
            $this->dropIndexIfExists($table, 'channels_user_id_index');
            $this->dropIndexIfExists($table, 'channels_subscriber_count_index');
        });


        /*
        |--------------------------------------------------------------------------
        | VIDEOS
        |--------------------------------------------------------------------------
        */

        Schema::table('videos', function (Blueprint $table) {
            $this->dropIndexIfExists($table, 'videos_slug_index');
            $this->dropIndexIfExists($table, 'videos_channel_id_index');
            $this->dropIndexIfExists($table, 'videos_category_id_index');
            $this->dropIndexIfExists($table, 'videos_status_index');
            $this->dropIndexIfExists($table, 'videos_visibility_index');
            $this->dropIndexIfExists(
                $table,
                'videos_published_at_index'
            );

            $this->dropIndexIfExists(
                $table,
                'videos_channel_status_visibility_index'
            );
        });


        /*
        |--------------------------------------------------------------------------
        | VIDEO VIEWS
        |--------------------------------------------------------------------------
        */

        Schema::table('video_views', function (Blueprint $table) {
            $this->dropIndexIfExists(
                $table,
                'video_views_video_id_index'
            );

            $this->dropIndexIfExists(
                $table,
                'video_views_user_id_index'
            );
        });


        /*
        |--------------------------------------------------------------------------
        | LIKES
        |--------------------------------------------------------------------------
        */

        Schema::table('likes', function (Blueprint $table) {
            $this->dropIndexIfExists(
                $table,
                'likes_video_id_index'
            );

            $this->dropIndexIfExists(
                $table,
                'likes_user_id_index'
            );
        });


        /*
        |--------------------------------------------------------------------------
        | COMMENTS
        |--------------------------------------------------------------------------
        */

        Schema::table('comments', function (Blueprint $table) {
            $this->dropIndexIfExists(
                $table,
                'comments_video_id_index'
            );

            $this->dropIndexIfExists(
                $table,
                'comments_user_id_index'
            );

            $this->dropIndexIfExists(
                $table,
                'comments_parent_id_index'
            );
        });


        /*
        |--------------------------------------------------------------------------
        | SUBSCRIPTIONS
        |--------------------------------------------------------------------------
        */

        Schema::table('subscriptions', function (Blueprint $table) {
            $this->dropIndexIfExists(
                $table,
                'subscriptions_user_id_index'
            );

            $this->dropIndexIfExists(
                $table,
                'subscriptions_channel_id_index'
            );

            $this->dropIndexIfExists(
                $table,
                'subscriptions_user_id_channel_id_unique'
            );
        });
    }


    /*
    |--------------------------------------------------------------------------
    | Check Index
    |--------------------------------------------------------------------------
    */

    private function indexExists(
        string $table,
        string $index
    ): bool {
        $connection = Schema::getConnection();

        $database = $connection->getDatabaseName();

        $result = $connection->select(
            "
            SELECT COUNT(*) AS count
            FROM information_schema.statistics
            WHERE table_schema = ?
            AND table_name = ?
            AND index_name = ?
            ",
            [
                $database,
                $table,
                $index,
            ]
        );

        return ((int) $result[0]->count) > 0;
    }


    /*
    |--------------------------------------------------------------------------
    | Drop Index Safely
    |--------------------------------------------------------------------------
    */

    private function dropIndexIfExists(
        Blueprint $table,
        string $index
    ): void {
        $table->dropIndex($index);
    }
};