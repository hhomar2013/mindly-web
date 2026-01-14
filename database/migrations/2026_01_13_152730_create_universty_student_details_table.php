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
        Schema::create('universty_student_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('education_stage_id')->constrained('education_stages')->onDelete('cascade');
            $table->foreignId('university_faculty_id')->nullable()->constrained('university_faculties')->onDelete('cascade');
            $table->foreignId('university_institute_id')->nullable()->constrained('university_institutes')->onDelete('cascade');
            $table->foreignId('university_academic_year_id')->nullable()->constrained('university_academic_years')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('universty_student_details');
    }
};
