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
        Schema::create('teacher_course_lesson_content_addons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tclc_id')->constrained('teacher_course_lesson_contents')->cascadeOnDelete();
            $table->foreignId('ct_id')->constrained('content_types')->cascadeOnDelete();
            $table->string('name');
            $table->string('link')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teacher_course_lesson_content_addons');
    }
};
