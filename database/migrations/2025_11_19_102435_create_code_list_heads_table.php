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
        Schema::create('code_list_heads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_course_overviews_id')->constrained('teacher_course_overviews')->cascadeOnDelete();
            $table->string('code_count');
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }
    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('code_list_heads');
    }
};
