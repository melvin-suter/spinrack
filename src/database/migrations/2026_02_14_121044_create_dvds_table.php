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
            $table->string('poster_path');
            $table->string('backdrop_path');
            $table->string('overview');
            $table->string('release');
            $table->integer('amount')->default(1);
            $table->integer('season')->nullable()->default(null);
            $table->enum('disc_type', ['blueray','dvd']);
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
