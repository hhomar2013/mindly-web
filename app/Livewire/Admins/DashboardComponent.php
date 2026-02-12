<?php

namespace App\Livewire\Admins;

use App\Models\Center;
use App\Models\Student;
use App\Models\Students;
use App\Models\StudentsLogs;
use App\Models\Teacher;
use App\Models\TeacherCourseOverview;
use Laravel\Sanctum\PersonalAccessToken;
use Livewire\Attributes\Layout;
use Livewire\Component;

class DashboardComponent extends Component
{
    protected $listeners = ['logoutStudent' => 'logoutStudent', 'refresh' => '$refresh'];
    public function logoutStudent($id)
    {
        $student = Students::find($id);
        if (!$student) return;
        // $student->update(['status' => false]);
        StudentsLogs::where('student_id', $id)
            ->where('is_active', true)
            ->update([
                'action' => 'logout',
                'is_active' => false
            ]);
        if (method_exists($student, 'tokens')) {
            $student->tokens()->delete();
        } else {
            PersonalAccessToken::where('tokenable_type', get_class($student))
                ->where('tokenable_id', $id)
                ->delete();
        }
        $this->dispatch('message', message: __('Student logged out successfully'));
        $this->dispatch('refresh');
    }



    #[Layout('layouts.app')]
    public function render()
    {
        $centers = Center::query()->get()->count();
        $teachers = Teacher::query()->get()->count();
        $students = Students::query()->get()->count();
        $studentLogs = StudentsLogs::query()->where('is_active', true)->latest()->get();
        $TeachersCourses = TeacherCourseOverview::query()->get();
        $totalcounts = $studentLogs->count();
        return view(
            'livewire.admins.dashboard-component',
            [
                'centers' => $centers,
                'teachers' => $teachers,
                'students' => $students,
                'totalcounts' => $totalcounts,
                'studentLogs' => $studentLogs,
                'TeachersCourses' => $TeachersCourses
            ]
        );
    }
}
