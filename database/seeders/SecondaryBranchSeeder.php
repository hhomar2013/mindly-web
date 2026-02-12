<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SecondaryBranch;
use App\Models\SecondaryTrack;
use Illuminate\Support\Facades\File;

class SecondaryBranchSeeder extends Seeder
{
    public function run(): void
    {
        $json = File::get(database_path('data/egyptian_academic_stages.json'));
        $data = json_decode($json, true);

        $tracks_data = $data['education_system'][0]['stages'][3]['tracks'];

        foreach ($tracks_data as $track_data) {
            $secondary_track = SecondaryTrack::where('track_id', $track_data['track_id'])->first();
            if ($secondary_track && isset($track_data['branches'])) {
                foreach ($track_data['branches'] as $branch_data) {
                    SecondaryBranch::create([
                        'secondary_track_id' => $secondary_track->id,
                        'branch_id' => $branch_data['branch_id'] ?? null,
                        'name' => [
                            'en' => $branch_data['name_en'],
                            'ar' => $branch_data['name_ar'],
                        ]
                    ]);
                }
            }
        }
    }
}