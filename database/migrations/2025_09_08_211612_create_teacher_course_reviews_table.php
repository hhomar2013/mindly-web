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
        Schema::create('teacher_course_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tco_id')->constrained('teacher_course_overviews')->cascadeOnDelete();
            $table->unsignedTinyInteger('star_number'); // 1-5
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->text('content')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teacher_course_reviews');
    }
};
