<?php

namespace Database\Seeders;

use App\Models\SecondaryBranch;
use App\Models\SecondarySubBranch;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class SecondarySubBranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = File::get(database_path('data/egyptian_academic_stages.json'));
        $data = json_decode($json, true);

        // مسار الوصول لبيانات المسار 'general_secondary'
        $pre_university_system = collect($data['education_system'])->firstWhere('id', 1);
        $secondary_stage_data = collect($pre_university_system['stages'])->firstWhere('id', 4);
        $general_secondary_track = collect($secondary_stage_data['tracks'])->firstWhere('track_id', 'general_secondary');

        if (!$general_secondary_track || !isset($general_secondary_track['branches'])) {
            return;
        }

        // 1. المرور على جميع الفروع في الثانوية العامة (علمي، أدبي)
        foreach ($general_secondary_track['branches'] as $branch_data) {

            // تخطي الفروع التي ليس بها تخصصات فرعية (مثل الفرع الأدبي)
            if (!isset($branch_data['sub_branches'])) {
                continue;
            }

            // 2. الحصول على ID الفرع الأم من قاعدة البيانات (Scientific Branch)
            $branch_db = SecondaryBranch::where('branch_id', $branch_data['branch_id'])->first();

            if (!$branch_db) {
                continue;
            }

            $secondary_branch_id = $branch_db->id;

            // 3. إدراج التخصصات الفرعية (علوم، رياضيات)
            foreach ($branch_data['sub_branches'] as $sub_branch) {
                SecondarySubBranch::query()->create([
                    'sub_branch_id' => $sub_branch['sub_branch_id'],
                    'secondary_branch_id' => $secondary_branch_id,
                    'name'             => [
                        'en' => $sub_branch['name_en'],
                        'ar' => $sub_branch['name_ar'],
                    ],
                ]);
            }
        }
    }
}
