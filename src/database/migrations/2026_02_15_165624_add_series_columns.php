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
        Schema::table('dvds', function (Blueprint $table) {
            $table->enum("media_type", ["tv", "movie"])->default("movie");
            $table->integer("series_min")->nullable();
            $table->integer("series_max")->nullable();
            $table->string("collection_id")->nullable();
            $table->string("collection_title")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dvds', function (Blueprint $table) {
            $table->dropColumn('media_type');
            $table->dropColumn('series_min');
            $table->dropColumn('series_max');
            $table->dropColumn('collection_id');
            $table->dropColumn('collection_title');
        });
    }
};
