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
        Schema::create('secondary_branches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('secondary_track_id')->constrained('secondary_tracks')->onDelete('cascade');
            $table->string('branch_id')->nullable();
            $table->json('name');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('secondary_branches');
    }
};
