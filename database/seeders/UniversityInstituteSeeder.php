<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EducationSystem;
use App\Models\EducationStage; // 💡 تم إضافة استيراد EducationStage
use App\Models\UniversityInstitute;
use Illuminate\Support\Facades\File;

class UniversityInstituteSeeder extends Seeder
{
    public function run(): void
    {
        // 1. جلب ID المرحلة الجامعية (Undergraduate)
        $undergraduate_stage = EducationStage::where('stage_id', 'undergraduate')->first();

        if (!$undergraduate_stage) {
            // توقف إذا لم يتم العثور على المرحلة (يجب تشغيل Seeder المراحل أولاً)
            return;
        }

        $stageId = $undergraduate_stage->id;

        // 2. قراءة بيانات الـ JSON
        $json = File::get(database_path('data/egyptian_academic_stages.json'));
        $data = json_decode($json, true);

        // 3. الوصول إلى بيانات المعاهد
        // (افتراضاً: نظام التعليم الجامعي هو education_system[1] والمرحلة الجامعية هي stages[0])
        $institute_data_array = $data['education_system'][1]['stages'][0]['institutes'] ?? [];

        foreach ($institute_data_array as $institute_data) {
            // استخدام updateOrCreate لتجنب تكرار البيانات وللتحديث إذا كانت موجودة
            UniversityInstitute::updateOrCreate(
                ['institute_id' => $institute_data['institute_id']], // شرط البحث
                [
                    // ✅ تم التعديل: استخدام education_stage_id بدلاً من education_system_id
                    'education_stage_id' => $stageId,
                    'name' => [
                        'en' => $institute_data['name_en'],
                        'ar' => $institute_data['name_ar'],
                    ],
                    'duration_years' => $institute_data['duration_years'],
                    // 'institute_id' موجود في شرط البحث
                ]
            );
        }
    }
}
