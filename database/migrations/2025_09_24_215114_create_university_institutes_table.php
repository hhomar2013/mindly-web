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
        Schema::create('university_institutes', function (Blueprint $table) {
            $table->id();

            // ✅ يجب إضافة هذا السطر لربط المعهد بالمرحلة الجامعية
            $table->foreignId('education_stage_id')
                ->constrained('education_stages')
                ->cascadeOnDelete();
            $table->string('institute_id')->unique();
            $table->json('name'); // لاحظ أننا نستخدم Spatie هنا
            $table->integer('duration_years');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('university_institutes');
    }
};
