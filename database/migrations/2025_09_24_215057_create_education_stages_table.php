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
        Schema::create('education_stages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('education_system_id')->constrained('education_systems')->onDelete('cascade');
            $table->string('stage_id')->unique();
            $table->json('name');
            $table->integer('duration_years')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('education_stages');
    }
};
