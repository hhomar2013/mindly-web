<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EducationStage;
use App\Models\SecondaryTrack;
use Illuminate\Support\Facades\File;

class SecondaryTrackSeeder extends Seeder
{
    public function run(): void
    {
        $json = File::get(database_path('data/egyptian_academic_stages.json'));
        $data = json_decode($json, true);

        $secondary_stage = EducationStage::where('stage_id', 'secondary')->first();
        if ($secondary_stage) {
            foreach ($data['education_system'][0]['stages'][3]['tracks'] as $track_data) {
                SecondaryTrack::create([
                    'education_stage_id' => $secondary_stage->id,
                    'track_id' => $track_data['track_id'],
                    'name' => [
                        'en' => $track_data['name_en'],
                        'ar' => $track_data['name_ar'],
                    ]
                ]);
            }
        }
    }
}