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
        Schema::create('teacher_secondary_details', function (Blueprint $table) {
            $table->id();
            // 1. المسار الأساسي (الثانوية العامة / البكالوريا / الأزهر)
            $table->foreignId('secondary_track_id')->constrained('secondary_tracks')->onDelete('cascade');

            // 2. الصف (أول ثانوي / ثاني بكالوريا / إلخ)
            $table->foreignId('secondary_grade_id')->constrained('secondary_grades')->onDelete('cascade');

            // 3. الفرع (علمي / أدبي) - يمكن أن يكون Nullable للمسارات التي ليس بها فروع
            $table->foreignId('secondary_branch_id')->nullable()->constrained('secondary_branches')->onDelete('cascade');
            // 3. الفرع (علمي علوم / علمى رياضيات في الصف الثالث الثانوي) - يمكن أن يكون Nullable للمسارات التي ليس بها فروع

            $table->foreignId('secondary_sub_branch_id')->nullable()->constrained('secondary_sub_branches');


            // 5. التخصص/القطاع (طب وعلوم حياة / صناعي) - يمكن أن يكون Nullable للمسارات التي ليس بها تخصصات دقيقة
            $table->foreignId('secondary_specialization_id')->nullable()->constrained('secondary_specializations')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teacher_secondary_details');
    }
};
