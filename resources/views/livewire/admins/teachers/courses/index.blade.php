<div>
    <div class="row">
        <div class="col-12 min-vh-100">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title">
                        <i class="fa-solid fa-book text-dark text-sm opacity-10"></i>&nbsp;
                        {{ __('Teacher Courses') }}
                    </h5>
                    <div class="text-start">
                        @include('tools.spinner')
                    </div>
                </div>
                @if ($action == 'show-course')
                    @include('livewire.admins.teachers.courses.teacher_courses')
                @elseif($action == 'add-course')
                    @include('livewire.admins.teachers.courses.add_courses')
                @elseif($action == 'subject-managment')
                    @include('livewire.admins.teachers.courses.subject-managment')
                @elseif($action == 'add-lesson')
                    @include('livewire.admins.teachers.courses.add_lesson')
                @elseif($action == 'add-lesson-content')
                    @include('livewire.admins.teachers.courses.add_lesson_content')
                @else
                    @include('livewire.admins.teachers.courses.show')
                @endif
            </div>
        </div>
    </div>
</div>
@script
    @include('tools.message')
@endscript
