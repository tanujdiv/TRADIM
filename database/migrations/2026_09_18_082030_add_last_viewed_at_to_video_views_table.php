<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('video_views', function (Blueprint $table) {
            $table->timestamp('last_viewed_at')
                ->nullable()
                ->after('ip_hash');

            $table->index([
                'video_id',
                'user_id',
                'last_viewed_at',
            ]);

            $table->index([
                'video_id',
                'session_id',
                'last_viewed_at',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('video_views', function (Blueprint $table) {
            $table->dropIndex([
                'video_id',
                'user_id',
                'last_viewed_at',
            ]);

            $table->dropIndex([
                'video_id',
                'session_id',
                'last_viewed_at',
            ]);

            $table->dropColumn('last_viewed_at');
        });
    }
};