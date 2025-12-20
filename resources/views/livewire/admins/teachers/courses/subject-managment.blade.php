<div class="row p-4">
    <div class="col-lg-4">
        <button class="btn btn-success" wire:click="setActionData('add-lesson')">
            {{ __('Add New Lesson') }} </button>
    </div>
    <div class="col-lg-8 text-end">
        <button class="btn btn-danger btn-rounded" wire:click="back('show-course','course_id')"> <i
                class="fas fa-arrow-left"></i>
        </button>
    </div>
    <div class="col-lg-12">
        <h4> <i class="fa-solid fa-book-open"></i> {{ $subjectShow->name }} </h4> <br>
    </div>
    <div class="col-lg-12">
        <div class="row">
            @forelse ($lessons as $lesson)
                <div class="col-lg-3">
                    <div class="card bg-gradient-info shadow p-2 text-white text-start min-h-100">
                        <div class="card-body">
                            <h5 class="card-title"> {{ $lesson->name }} </h5>
                            <br>

                            <button class="btn btn-dark btn-rounded" wire:click="addLessonContent({{ $lesson->id }})">
                                <i class="fa-solid fa-sheet-plastic"></i>
                            </button>&nbsp;
                            <button class="btn btn-success btn-rounded" wire:click="editLesson({{ $lesson->id }})">
                                <i class="fas fa-edit"></i>
                            </button>&nbsp;
                            <button class="btn btn-danger btn-rounded"
                                onclick="confirmDelete({{ $lesson->id }},'deleteLesson')">
                                <i class="fas fa-trash"></i>
                            </button>

                        </div>

                    </div>
                </div>
            @empty
                <div class="col-lg-12">
                    <div class="alert alert-danger text-center text-white bg-rounded"> {{ __('No Lessons Found') }}
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>
