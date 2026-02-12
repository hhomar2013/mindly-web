<?php

namespace Database\Seeders;

use App\Models\SecondarySpecialization;
use App\Models\SecondaryTrack;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
class SecondarySpecializationSeeder extends Seeder
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

            // تخطي المسارات التي ليس بها تخصصات (مثل الثانوية العامة والأزهرية)
            if (!isset($track_data['specializations'])) {
                continue;
            }

            // البحث عن سجل المسار في قاعدة البيانات
            $track_db = SecondaryTrack::where('track_id', $track_data['track_id'])->first();

            if (!$track_db) {
                continue;
            }

            $secondary_track_id = $track_db->id;

            // إدراج التخصصات (Specializations) لهذا المسار
            foreach ($track_data['specializations'] as $spec) {
                SecondarySpecialization::updateOrCreate(
                    ['spec_id' => $spec['spec_id']],
                    [
                        'secondary_track_id' => $secondary_track_id,
                        'name'            => [
                            'en' => $spec['name_en'],
                            'ar' => $spec['name_ar'],
                        ],
                    ]
                );
            }
        }
    }
}
