<div class="card min-vh-100">
    <div class="row p-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4> <i class="fa-solid fa-book-open-reader"></i> {{ $teacher->name }} </h4>
            <div class="text-start">
                <button class="btn btn-danger btn-rounded" wire:click="back('show',['quiz_teacher_id'],['teacher_id'])">
                    <i class="fas fa-arrow-left"></i>
                </button>
            </div>
        </div>
        <div class="text-start">
            <button class="btn btn-success btn-rounded" wire:click="createQuiz({{ $teacher->id }})">
                <i class="fa-solid fa-plus"></i> {{ __('Create Quiz') }}
            </button>
        </div>
    </div>



    <div class="card-body">

        <div class="row p-4">
            @forelse ($teacherQuizes as $quiz)
                <div class="col-lg-4">
                    <div class="card bg-gradient-info">
                        <div class="card-body">
                            <h4 class="card-title text-white">{{ $quiz->title }}</h4>
                            <h6 class="card-title text-white"> {{ __('Quiz Questions Count') }} :
                                {{ $quiz->questions->count() }}</h6>
                            <h6 class="card-title text-white"> {{ __('Quiz Total Score') }} :
                                {{ $quiz->questions->sum('score') }}</h6>
                        </div>
                        <div class="card-footer">
                            <button class="btn btn-dark btn-rounded" wire:click="editQuiz({{ $quiz->id }})">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                            <button class="btn btn-success btn-rounded"
                                wire:click="createQuizQuestions({{ $quiz->id }})">
                                <i class="fa-solid fa-plus"></i>
                            </button>
                            <button class="btn btn-danger  btn-rounded"
                                onclick="confirmDelete({{ $quiz->id }} ,'deleteQuiz')">
                                <i class="fa fa-trash"></i>
                            </button>
                            @if ($quiz->questions->count() > 0)
                            <br>
                            <label class="text-white" for="">{{ __('Status')}}</label>
                                @livewire('switcher', ['model' => $quiz, 'field' => 'state'])
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert bg-gradient-danger">
                        <h4 class="text-white text-center p-2"> {{ __('No Quizes Found') }}</h4>
                    </div>
                </div>
            @endforelse
        </div>

    </div>
</div>
