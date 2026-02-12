<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EducationStage;
use App\Models\StageGrade;
use Illuminate\Support\Facades\File;

class StageGradeSeeder extends Seeder
{
    public function run(): void
    {
        $json = File::get(database_path('data/egyptian_academic_stages.json'));
        $data = json_decode($json, true);

        $stages = $data['education_system'][0]['stages'];

        foreach ($stages as $stage_data) {
            if (isset($stage_data['grades'])) {
                $education_stage = EducationStage::query()->find($stage_data['id']);
                foreach ($stage_data['grades'] as $grade_data) {
                    StageGrade::create([
                        'education_stage_id' => $education_stage->id,
                        'grade_id' => $grade_data['grade_id'],
                        'name' => [
                            'en' => $grade_data['name_en'],
                            'ar' => $grade_data['name_ar'],
                        ]
                    ]);
                }
            }
        }
    }
}
