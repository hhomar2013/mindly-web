<?php

namespace Database\Seeders;

use App\Models\EducationStage;
use App\Models\UniversityAcademicYear;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UniversityAYSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $undergraduate_stage = EducationStage::where('stage_id', 'undergraduate')->first();

        if (!$undergraduate_stage) {
            return;
        }
        $stageId = $undergraduate_stage->id;
        $academicYears = [
            ['year_number' => 1, 'name_en' => 'First Academic Year', 'name_ar' => 'السنة الجامعية الأولى'],
            ['year_number' => 2, 'name_en' => 'Second Academic Year', 'name_ar' => 'السنة الجامعية الثانية'],
            ['year_number' => 3, 'name_en' => 'Third Academic Year', 'name_ar' => 'السنة الجامعية الثالثة'],
            ['year_number' => 4, 'name_en' => 'Fourth Academic Year', 'name_ar' => 'السنة الجامعية الرابعة'],
            ['year_number' => 5, 'name_en' => 'Fifth Academic Year', 'name_ar' => 'السنة الجامعية الخامسة'],
            ['year_number' => 6, 'name_en' => 'Sixth Academic Year', 'name_ar' => 'السنة الجامعية السادسة'],
            ['year_number' => 7, 'name_en' => 'Seventh Academic Year', 'name_ar' => 'السنة الجامعية السابعة'],
        ];
        foreach ($academicYears as $yearData) {
            UniversityAcademicYear::query()->create([
                'year_number' => $yearData['year_number'],
                'education_stage_id' => $stageId,
                'name' => [
                    'en' => $yearData['name_en'],
                        'ar' => $yearData['name_ar'],
                    ]
                ]
            );
        }
    }
}
