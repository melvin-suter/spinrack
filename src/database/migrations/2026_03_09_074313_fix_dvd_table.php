<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('dvd_genre', 'dvd_id')) {
                Schema::table('dvd_genre', function (Blueprint $table) {
                    $table->foreignId('dvd_id')->constrained()->cascadeOnDelete();
                });
        }
        if (Schema::hasColumn('dvd_genre', 'tag_id')) {
            Schema::table('dvd_genre', function (Blueprint $table) {
                $table->dropForeign(['tag_id']);
                $table->dropColumn('tag_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasColumn('dvd_genre', 'tag_id')) {
            Schema::table('dvd_genre', function (Blueprint $table) {
                $table->foreignId('tag_id')->constrained()->cascadeOnDelete();
            });
        }
        if (Schema::hasColumn('dvd_genre', 'dvd_id')) {
            Schema::table('dvd_genre', function (Blueprint $table) {
                $table->dropForeign(['dvd_id']);
                $table->dropColumn('dvd_id');
            });
        }
    }
};
