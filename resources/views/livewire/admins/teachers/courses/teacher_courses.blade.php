<div class="row p-4">
    <div class="col-lg-4">
        <button class="btn btn-success" wire:click="addCourse"> {{ __('Add Course') }} </button>
    </div>
    <div class="col-lg-8 text-end">
        <button class="btn btn-danger btn-rounded" wire:click="back('index','teacher_id')"> <i
                class="fas fa-arrow-left"></i>
        </button>
    </div>
    <div class="col-lg-12">
        <h4> <i class="fas fa-user"></i> {{ $teacher->name }} </h4> <br>
    </div>
    @forelse ($courses as $course)
        <div class="col-lg-3">
            <div class="card bg-gradient-primary p-2 text-white text-center min-h-100">

                <div class="card-body text-center">
                    <img src="{{ $course->image }}"
                        style="width: 120px; height: 120px; object-fit: cover; border-radius: 50%;">
                    <p class="text-white"> <b>{{ $course->name }}</b> </p>
                    <p class="text-light">
                        {{ __('Subject') }}: <b>{{ $course->subject->name ?? 'N/A' }}</b>
                    </p>
                    {{-- @if ($course->education)
                        <p class="text-light">
                            <b>{{ $course->education-> }}</b>
                        </p>
                    @endif --}}
                </div>

                <div class="card-footer d-flex justify-content-start">
                    <button class="btn btn-success btn-rounded" wire:click.prevent="editCourse({{ $course->id }})">
                        <i class="fa-solid fa-edit"></i>
                    </button>&nbsp;
                    <button class="btn btn-danger btn-rounded"
                        onclick="confirmDelete({{ $course->id }},'deleteCourse')">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </div>
                <button class="btn btn-warning btn-rounded" wire:click.prevent="subjectManagment({{ $course->id }})">
                    <i class="fa-solid fa-cogs"></i> {{ __('Subject Management') }}
                </button>&nbsp;
            </div>

        </div>
    @empty
        <div class="col-12">
            <div class="card bg-warning  text-center p-5">
                <h5 class="text-white"><b> {{ __('Corsus Not Found') }}</b></h5>
            </div>
        </div>
    @endforelse
</div>
