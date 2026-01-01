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
        Schema::create('secondary_specializations', function (Blueprint $table) {
            $table->id();

            // المفتاح الأجنبي لربط التخصص بالمسار (البكالوريا المصرية، الثانوي الفني)
            $table->foreignId('secondary_track_id')
                ->constrained('secondary_tracks')
                ->cascadeOnDelete();

            $table->string('spec_id')->unique(); // المعرف الفريد للتخصص (مثل medical_sector)
            $table->json('name');

            $table->timestamps();

            $table->index('spec_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('secondary_specializations');
    }
};
