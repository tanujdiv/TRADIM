<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('video_views', function (Blueprint $table) {
            if (Schema::hasColumn('video_views', 'watch_seconds')) {
                $table->dropColumn('watch_seconds');
            }
        });
    }

    public function down(): void
    {
        Schema::table('video_views', function (Blueprint $table) {
            $table->unsignedInteger('watch_seconds')
                ->default(0)
                ->after('watched_seconds');
        });
    }
};