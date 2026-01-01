<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\EducationSystem;
use Illuminate\Http\Request;

class AcademicDataController extends Controller
{
    /**
     * جلب هيكل البيانات الأكاديمي كاملاً بتنسيق هرمي (Denormalization).
     */
    public function getAcademicStructure()
    {
        // التحميل المتعجل لجميع العلاقات المطلوبة
        $systems = EducationSystem::with([
            'stages' => function ($query) {
                $query->with([
                    'grades',
                    'tracks' => function ($query) {
                        $query->with([
                            'grades',
                            'branches' => function ($query) {
                                $query->with('subBranches');
                            },
                            'specializations',
                        ]);
                    },
                    'faculties',
                    'institutes', // تستخدم Spatie
                    'universityAcademicYears'
                ]);
            }
        ])->orderBy('id')->get();

        $transformedData = $this->transformSystems($systems);

        return response()->json([
            'education_system' => $transformedData,
        ]);
    }

    // ----------------------------------------------------------------------
    // دوال التحويل (Mapping) لإعادة بناء الهيكل بالتنسيق المطلوب
    // ----------------------------------------------------------------------

    private function transformSystems($systems): array
    {
        return $systems->map(function ($system) {
            return [
                'id' => $system->id,
                'name_en' => $system->getTranslation('name', 'en'),
                'name_ar' => $system->getTranslation('name', 'ar'),
                'stages' => $this->transformStages($system->stages),
            ];
        })->toArray();
    }

    private function transformStages($stages): array
    {
        return $stages->map(function ($stage) {
            $stageData = [
                'id' => $stage->id,
                'stage_id' => $stage->stage_id,
                'name_en' => $stage->getTranslation('name', 'en'),
                'name_ar' => $stage->getTranslation('name', 'ar'),
            ];

            if ($stage->education_system_id === 1) { // التعليم قبل الجامعي
                $stageData['duration_years'] = $stage->duration_years;

                if ($stage->grades->isNotEmpty()) {
                    $stageData['grades'] = $stage->grades->map(fn($grade) => [
                        'id' => $grade->id,
                        'grade_id' => $grade->grade_id,
                        'name_en' => $grade->getTranslation('name', 'en'),
                        'name_ar' => $grade->getTranslation('name', 'ar'),
                    ])->toArray();
                }

                if ($stage->stage_id === 'secondary') {
                    $stageData['tracks'] = $this->transformTracks($stage->tracks);
                }
            }

            if ($stage->education_system_id === 2) { // التعليم الجامعي
                $stageData['faculties'] = $this->transformFaculties($stage->faculties);
                $stageData['institutes'] = $this->transformInstitutes($stage->institutes);
                $stageData['academic_years'] = $this->transformAcademicYears($stage->universityAcademicYears);
            }

            return $stageData;
        })->toArray();
    }

    private function transformTracks($tracks): array
    {
        return $tracks->map(function ($track) {
            $trackData = [
                'id' => $track->id,
                'track_id' => $track->track_id,
                'name_en' => $track->getTranslation('name', 'en'),
                'name_ar' => $track->getTranslation('name', 'ar'),
            ];

            $trackData['grades'] = $track->grades->map(fn($grade) => [
                'id' => $grade->id,
                'grade_id' => $grade->grade_id,
                'name_en' => $grade->getTranslation('name', 'en'),
                'name_ar' => $grade->getTranslation('name', 'ar'),
            ])->toArray();

            if ($track->branches->isNotEmpty()) {
                $trackData['branches'] = $this->transformBranches($track->branches);
            }

            if ($track->specializations->isNotEmpty()) {
                $trackData['specializations'] = $track->specializations->map(fn($spec) => [
                    'id' => $spec->id,
                    'spec_id' => $spec->spec_id,
                    'name_en' => $spec->getTranslation('name', 'en'),
                    'name_ar' => $spec->getTranslation('name', 'ar'),
                ])->toArray();
            }

            return $trackData;
        })->toArray();
    }

    private function transformBranches($branches): array
    {
        return $branches->map(function ($branch) {
            $branchData = [
                'id' => $branch->id,
                'branch_id' => $branch->branch_id,
                'name_en' => $branch->getTranslation('name', 'en'),
                'name_ar' => $branch->getTranslation('name', 'ar'),
            ];

            // Sub-branches تستخدم Spatie (عمود JSON اسمه 'name')
            if ($branch->subBranches->isNotEmpty()) {
                $branchData['sub_branches'] = $branch->subBranches->map(function ($subBranch) {
                    $name = $subBranch->name; // يجب أن تعيد الـ JSON Array

                    return [
                        'id' => $subBranch->id,
                        'sub_branch_id' => $subBranch->sub_branch_id,
                        // ✅ استخراج القيم النصية من مصفوفة Spatie
                        'name_en' =>  $subBranch->getTranslation('name', 'en') ?? null,
                        'name_ar' => $subBranch->getTranslation('name', 'ar') ?? null,
                    ];
                })->toArray();
            }

            return $branchData;
        })->toArray();
    }

    // تحويل الكليات (Faculties)
    private function transformFaculties($faculties): array
    {
        return $faculties->map(fn($faculty) => [
            'id' => $faculty->id,
            'faculty_id' => $faculty->faculty_id,
            'name_en' => $faculty->getTranslation('name', 'en') ?? null,
            'name_ar' => $faculty->getTranslation('name', 'ar') ?? null,
            'duration_years' => $faculty->duration_years ?? null,
        ])->toArray();
    }

    // تحويل المعاهد (Institutes)
    private function transformInstitutes($institutes): array
    {
        return $institutes->map(function ($institute) {
            $name = $institute->name; // يجب أن تعيد الـ JSON Array (Spatie)

            return [
                'id' => $institute->id,
                'institute_id' => $institute->institute_id,
                // ✅ استخراج القيم النصية من مصفوفة Spatie
                'name_en' => $institute->getTranslation('name', 'en') ?? null,
                'name_ar' => $institute->getTranslation('name', 'ar') ?? null,
                'duration_years' => $institute->duration_years ?? null,
            ];
        })->toArray();
    }

    // تحويل سنوات الدراسة الجامعية (Academic Years)
    private function transformAcademicYears($years): array
    {
        return $years->map(fn($year) => [
            'id' => $year->id,
            'year_number' => $year->year_number,
            'name_en' => $year->getTranslation('name', 'en') ?? null,
            'name_ar' => $year->getTranslation('name', 'ar') ?? null,
        ])->toArray();
    }
}
