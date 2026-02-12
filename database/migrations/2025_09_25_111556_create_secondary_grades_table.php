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
        Schema::create('secondary_grades', function (Blueprint $table) {
            $table->id();

            // المفتاح الأجنبي لربط الصف بالمسار (الثانوي العام، الأزهر، البكالوريا، إلخ)
            $table->foreignId('secondary_track_id')
                ->constrained('secondary_tracks')
                ->cascadeOnDelete();

            $table->string('grade_id')->unique(); // المعرف الفريد للصف (مثل secondary_1)
            $table->json('name');

            $table->timestamps();

            $table->index('grade_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('secondary_grades');
    }
};
