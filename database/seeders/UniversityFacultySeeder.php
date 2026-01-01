<?php

namespace Database\Seeders;

use App\Models\EducationStage;
use Illuminate\Database\Seeder;
use App\Models\EducationSystem;
use App\Models\UniversityFaculty;
use Illuminate\Support\Facades\File;

class UniversityFacultySeeder extends Seeder
{
    public function run(): void
    {
        // 1. جلب ID المرحلة الجامعية (Undergraduate Stage)
        $undergraduate_stage = EducationStage::where('stage_id', 'undergraduate')->first();

        if (!$undergraduate_stage) {
            // توقف إذا لم يتم العثور على المرحلة (يجب تشغيل Seeder المراحل أولاً)
            return;
        }

        $stageId = $undergraduate_stage->id;

        // 2. قراءة بيانات الـ JSON
        $json = File::get(database_path('data/egyptian_academic_stages.json'));
        $data = json_decode($json, true);

        // ... مسار الوصول لبيانات الكليات في ملف JSON ...
        // (نفترض أن مسار الوصول مشابه لما استخدمناه للمعاهد سابقاً)
        $university_system_data = collect($data['education_system'])->firstWhere('id', 2); // ID نظام التعليم الجامعي
        $faculty_data = collect($university_system_data['stages'])->firstWhere('stage_id', 'undergraduate');

        if (!$faculty_data || !isset($faculty_data['faculties'])) {
            return;
        }


        // 3. إدراج الكليات
        foreach ($faculty_data['faculties'] as $faculty) {
            UniversityFaculty::updateOrCreate(
                ['faculty_id' => $faculty["faculty_id"]],
                [
                    // ✅ تم التعديل: استخدام education_stage_id بدلاً من education_system_id
                    'education_stage_id' => $stageId,
                    'name' => [ // نفترض أنك تستخدم Spatie هنا
                        'en' => $faculty['name_en'],
                        'ar' => $faculty['name_ar'],
                    ],
                    'duration_years' => $faculty['duration_years'] ?? 4, // ✅ إضافة المدة مع قيمة افتراضية

                ]
            );
        }
    }
}
