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
        Schema::create('job_status', function (Blueprint $table) {
            $table->id();

            $table->string('type');          // FillMeta
            $table->integer('reference_id'); // dvd id

            $table->enum('status', [
                'pending',
                'running',
                'completed',
                'failed'
            ])->default('pending');

            $table->text('error')->nullable();

            $table->timestamp('started_at')->nullable();
            $table->timestamp('finished_at')->nullable();

            $table->timestamps();
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_status');
    }
};
