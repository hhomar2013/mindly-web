<?php

namespace Database\Seeders;

use App\Models\SecondaryGrade;
use App\Models\SecondaryTrack;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class SecondaryGradeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = File::get(database_path('data/egyptian_academic_stages.json'));
        $data = json_decode($json, true);

        // مسار الوصول لبيانات المسارات الثانوية في الـ JSON المحدث
        $pre_university_system = collect($data['education_system'])->firstWhere('id', 1);
        $secondary_stage_data = collect($pre_university_system['stages'])->firstWhere('id', 4);

        if (!$secondary_stage_data) {
            return;
        }

        // المرور على جميع المسارات (Tracks)
        foreach ($secondary_stage_data['tracks'] as $track_data) {

            // البحث عن سجل المسار في قاعدة البيانات
            $track_db = SecondaryTrack::where('track_id', $track_data['track_id'])->first();

            if (!$track_db || !isset($track_data['grades'])) {
                continue;
            }

            $secondary_track_id = $track_db->id;

            // إدراج الصفوف (Grades) لهذا المسار
            foreach ($track_data['grades'] as $grade) {
                SecondaryGrade::updateOrCreate(
                    ['grade_id' => $grade['grade_id']],
                    [
                        'secondary_track_id' => $secondary_track_id,
                        'name'            => [
                            'en' => $grade['name_en'],
                            'ar' => $grade['name_ar'],
                        ],
                    ]
                );
            }
        }
    }
}
