<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\EducationStage;
use App\Models\EducationSystem;
use Illuminate\Support\Facades\File;

class EducationStageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = File::get(database_path('data/egyptian_academic_stages.json'));
        $data = json_decode($json, true);

        $pre_university_system_id = EducationSystem::where('id', 1)->first()->id;
        // dd($data);
        $i = 0;
        foreach ($data['education_system'] as $system_data) {
            // $i++;
            foreach ($system_data['stages'] as $stage_data) {
                EducationStage::create([
                    'education_system_id' => $system_data['id'],
                    'stage_id' => $stage_data['stage_id'],
                    'name' => [
                        'en' => $stage_data['name_en'],
                        'ar' => $stage_data['name_ar'],
                    ],
                    'duration_years' => $stage_data['duration_years'] ?? null,
                ]);
            }
        }
    }
}
