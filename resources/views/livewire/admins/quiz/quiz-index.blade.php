<div>

    <div class="row">
        <div class="col-12 min-vh-100">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title">
                        <i class="fa-solid fa-book text-dark "></i>&nbsp;
                        {{ __('Quiz') }}
                    </h5>
                    <div class="text-start">
                        @include('tools.spinner')
                    </div>
                </div>
                @if ($action == 'show')
                    @include('livewire.admins.quiz.quiz-show')
                @elseif ($action == 'show-teacher-quizs')
                    @include('livewire.admins.quiz.show-teacher-quizs')
                @elseif($action == 'create-quiz')
                    @include('livewire.admins.quiz.create-quiz')
                @elseif($action == 'create-quiz-questions')
                    @include('livewire.admins.quiz.create-quiz-questions')
                @endif
            </div>
        </div>
    </div>
</div>
@script
    @include('tools.message')
@endscript

@include('tools.confimDelete', ['method' => 'deleteQuiz'])

@include('tools.confimDelete', ['method' => 'deleteQuestion'])

@include('tools.confimDelete', ['method' => 'removeQuestionAnswer'])
