<?php

namespace App\Observers;

use App\Models\TeacherCourseOverview;
use Illuminate\Support\Facades\Cache;

class TeacherCourseOverviewObserver
{
    /**
     * Handle the TeacherCourseOverview "created" event.
     */
    public function created(TeacherCourseOverview $teacherCourseOverview): void
    {
        $this->clearCache();
    }

    /**
     * Handle the TeacherCourseOverview "updated" event.
     */
    public function updated(TeacherCourseOverview $teacherCourseOverview): void
    {
        //
    }

    /**
     * Handle the TeacherCourseOverview "deleted" event.
     */
    public function deleted(TeacherCourseOverview $teacherCourseOverview): void
    {
        //
    }

    /**
     * Handle the TeacherCourseOverview "restored" event.
     */
    public function restored(TeacherCourseOverview $teacherCourseOverview): void
    {
        //
    }

    /**
     * Handle the TeacherCourseOverview "force deleted" event.
     */
    public function forceDeleted(TeacherCourseOverview $teacherCourseOverview): void
    {
        //
    }


    private function clearCache($id = null)
    {
        Cache::forget('home_public_sections'); 
        if ($id) {
            Cache::forget("teacher_profile_{$id}");
        }
    }
}
