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
        Schema::create('dvds', function (Blueprint $table) {
            $table->id();
            $table->string('tmdbid');
            $table->string('title');
            $table->string('search_title')->nullable();
            $table->string('poster_path')->nullable();
            $table->string('overview')->nullable();
            $table->string('release')->nullable();
            $table->integer('season')->nullable()->default(null);
            $table->integer('season_name')->nullable()->default(null);
            $table->enum('disc_type', ['blueray','dvd']);
            $table->enum("media_type", ["tv", "movie"])->default("movie");
            $table->integer("series_min")->nullable();
            $table->integer("series_max")->nullable();
            $table->string("collection_id")->nullable();
            $table->string("collection_title")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dvds');
    }
};
