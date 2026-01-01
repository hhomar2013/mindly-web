<?php

namespace Database\Seeders;

use App\Models\EducationSystem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class EducationSystemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = File::get(database_path('data/egyptian_academic_stages.json'));
        $data = json_decode($json, true);

        foreach ($data['education_system'] as $system_data) {
            EducationSystem::create([
                'name' => [
                    'en' => $system_data['name_en'],
                    'ar' => $system_data['name_ar'],
                ],
            ]);
        }
    }
}
