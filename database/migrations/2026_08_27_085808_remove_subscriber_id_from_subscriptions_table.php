<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // 1. Drop any foreign keys referencing subscriber_id if present
        $foreignKeys = DB::select("
            SELECT CONSTRAINT_NAME 
            FROM information_schema.KEY_COLUMN_USAGE 
            WHERE TABLE_SCHEMA = DATABASE() 
              AND TABLE_NAME = 'subscriptions' 
              AND COLUMN_NAME = 'subscriber_id' 
              AND REFERENCED_TABLE_NAME IS NOT NULL
        ");

        foreach ($foreignKeys as $fk) {
            DB::statement("ALTER TABLE `subscriptions` DROP FOREIGN KEY `{$fk->CONSTRAINT_NAME}`");
        }

        // 2. Drop any indexes associated with subscriber_id
        $indexes = DB::select("
            SELECT DISTINCT INDEX_NAME 
            FROM information_schema.STATISTICS 
            WHERE TABLE_SCHEMA = DATABASE() 
              AND TABLE_NAME = 'subscriptions' 
              AND COLUMN_NAME = 'subscriber_id'
              AND INDEX_NAME != 'PRIMARY'
        ");

        foreach ($indexes as $index) {
            DB::statement("ALTER TABLE `subscriptions` DROP INDEX `{$index->INDEX_NAME}`");
        }

        // 3. Drop the column safely
        if (Schema::hasColumn('subscriptions', 'subscriber_id')) {
            Schema::table('subscriptions', function (Blueprint $table) {
                $table->dropColumn('subscriber_id');
            });
        }
    }

    public function down(): void
    {
        if (!Schema::hasColumn('subscriptions', 'subscriber_id')) {
            Schema::table('subscriptions', function (Blueprint $table) {
                $table->unsignedBigInteger('subscriber_id')->nullable()->after('user_id');
            });
        }
    }
};