<?php

namespace App\Livewire\Admins;

use App\Models\Center;
use App\Models\Student;
use App\Models\Students;
use App\Models\StudentsLogs;
use App\Models\Teacher;
use Livewire\Attributes\Layout;
use Livewire\Component;

class DashboardComponent extends Component
{
    #[Layout('layouts.app')]
    public function render()
    {
        $centers = Center::query()->get()->count();
        $teachers = Teacher::query()->get()->count();
        $students = Students::query()->get()->count();
        $studentLogs = StudentsLogs::query()->where('is_active', true)->latest()->get();
        $totalcounts = $studentLogs->count();
        return view('livewire.admins.dashboard-component', ['centers' => $centers, 'teachers' => $teachers, 'students' => $students, 'totalcounts' => $totalcounts, 'studentLogs' => $studentLogs]);
    }
}
