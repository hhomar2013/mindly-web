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
        Schema::create('secondary_tracks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('education_stage_id')->constrained('education_stages')->onDelete('cascade');
            $table->string('track_id')->unique();
            $table->json('name');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('secondary_tracks');
    }
};
