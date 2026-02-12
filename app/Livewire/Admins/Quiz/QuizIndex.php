<?php

namespace App\Livewire\Admins\Quiz;

use App\Helpers\switchActions;
use App\Models\exam;
use App\Models\exam_questions;
use App\Models\Teacher;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class QuizIndex extends Component
{
    use WithFileUploads, WithPagination, switchActions;
    #[Layout('layouts.app')]
    #[Title('Quiz')]
    #[Url()]
    public $action = 'show';
    public $update = false;
    public $search = '';
    public $sort = 'id';
    public $direction = 'desc';
    public $page = 1;
    public $per_page = 10;
    public $teacher_id;
    public $quizTitle;
    public $teacher;
    public $quizQuestions;
    public $teacherQuizes;
    public $quizDuration;
    public $quizQuestionsCount;
    public $quizTotalScore;
    public $quiz_id;
    public $quiz;
    public $questionTitle;
    public $questionImage, $old_questionImage;
    public $questionCorrect;
    public $questionScore;
    public $answerScore;
    public $questionType;
    public $questionAnswer;
    public $questionAnswers = [];
    public $isImage = false;
    public $correctAnswerIndex = null;
    public $questionTypes = [
        'trueOrFalse' => 'True Or False',
        'choose' => 'Choose',
    ];


    protected $listeners = ['deleteQuestion' => 'deleteQuestion', 'deleteQuiz' => 'deleteQuiz', 'removeQuestionAnswer' => 'removeQuestionAnswer'];


    public function newQuestion()
    {
        $this->reset([
            'questionTitle',
            'questionType',
            'questionAnswers',
            'correctAnswerIndex',
            'answerScore',
            'isImage',
            'questionImage',
            'update',
            'old_questionImage',
        ]);
    }

    public function deleteQuestion($id)
    {
        exam_questions::destroy($id);
        $this->dispatch('message', message: __('Question deleted successfully!'));
        $this->createQuizQuestions($this->quiz_id);
    }

    public function deleteQuiz($id)
    {
        exam::destroy($id);
        $this->dispatch('message', message: __('Quiz deleted successfully!'));
        $this->showTeacherQuiz($this->teacher_id);
    }

    //editQuestions
    public function editQuestion($questionId)
    {
        $this->update = true;
        $this->quizQuestions = exam_questions::findOrFail($questionId);

        // Basic fields
        $this->questionTitle = $this->quizQuestions->text;
        $this->questionType  = $this->quizQuestions->type;
        $this->answerScore   = $this->quizQuestions->score;

        $this->old_questionImage = $this->quizQuestions->image;
        $this->isImage = !empty($this->quizQuestions->image);

        // Reset
        $this->questionAnswers = [];
        $this->correctAnswerIndex = null;

        $options = json_decode($this->quizQuestions->options, true);

        // ================= TRUE / FALSE =================
        if ($this->questionType === 'trueOrFalse') {

            $this->questionAnswers[] = [
                'title'   => null,
                'correct' => $this->quizQuestions->correct_answer, // true / false
            ];
        }

        // ================= CHOOSE =================
        if ($this->questionType === 'choose') {

            foreach ($options as $index => $option) {
                $this->questionAnswers[] = [
                    'title' => $option,
                ];

                if ($option === $this->quizQuestions->correct_answer) {
                    $this->correctAnswerIndex = $index;
                }
            }
        }
    }

    //updateQuestion
    public function updateQuestion()
    {
        // ================= Validation =================
        $rules = [
            'questionType'  => 'required|in:trueOrFalse,choose',
            'questionTitle' => 'required|string|min:3',
            'answerScore'   => 'required|integer|min:1',
        ];

        if ($this->questionType === 'trueOrFalse') {
            $rules['questionAnswers.0.correct'] = 'required|in:true,false';
        }

        if ($this->questionType === 'choose') {
            $rules['questionAnswers'] = 'required|array|min:2';
            $rules['correctAnswerIndex'] = 'required|integer';
            foreach ($this->questionAnswers as $key => $answer) {
                $rules["questionAnswers.$key.title"] = 'required|string|min:1';
            }
        }

        $this->validate($rules);

        // ================= Prepare Data =================
        $options = [];
        $correctAnswer = null;

        // TRUE / FALSE
        if ($this->questionType === 'trueOrFalse') {
            $options = ['true', 'false'];
            $correctAnswer = $this->questionAnswers[0]['correct'];
        }

        // CHOOSE
        if ($this->questionType === 'choose') {
            foreach ($this->questionAnswers as $i => $answer) {
                $options[] = $answer['title'];
                if ($i == $this->correctAnswerIndex) {
                    $correctAnswer = $answer['title'];
                }
            }
        }

        // ================= Image Upload =================
        $imagePath = $this->old_questionImage;

        if ($this->isImage && $this->questionImage) {
            $imagePath = $this->questionImage->store('questions', 'public');
        }

        // ================= Update Question =================
        $update =  $this->quizQuestions->update([
            'text'           => $this->questionTitle,
            'image'          => $imagePath,
            'options'        => json_encode($options),
            'type'           => $this->questionType,
            'correct_answer' => $correctAnswer,
            'score'          => $this->answerScore,
        ]);

        if ($update) {
            $this->reset([
                'questionTitle',
                'questionType',
                'questionAnswers',
                'correctAnswerIndex',
                'answerScore',
                'isImage',
                'questionImage',
                'update',
                'old_questionImage',
            ]);
            $this->dispatch('message', message: __('Question updated successfully!'));
            $this->createQuizQuestions($this->quiz_id);
        }
    }



    //saveQuestion
    public function saveQuestion()
    {
        // ================= Validation =================
        $rules = [
            'questionType'  => 'required|in:trueOrFalse,choose',
            'questionTitle' => 'required|string|min:3',
            'answerScore'   => 'required|integer|min:1',
        ];

        if ($this->questionType === 'trueOrFalse') {
            $rules['questionAnswers.0.correct'] = 'required|in:true,false';
        }

        if ($this->questionType === 'choose') {
            $rules['questionAnswers'] = 'required|array|min:2';
            $rules['correctAnswerIndex'] = 'required|integer';
            foreach ($this->questionAnswers as $key => $answer) {
                $rules["questionAnswers.$key.title"] = 'required|string|min:1';
            }
        }

        $this->validate($rules);

        // ================= Prepare Data =================
        $options = [];
        $correctAnswer = null;

        // TRUE / FALSE
        if ($this->questionType === 'trueOrFalse') {
            $options = ['true', 'false'];
            $correctAnswer = $this->questionAnswers[0]['correct'];
        }

        // CHOOSE
        if ($this->questionType === 'choose') {
            foreach ($this->questionAnswers as $i => $answer) {
                $options[] = $answer['title'];
                if ($i == $this->correctAnswerIndex) {
                    $correctAnswer = $answer['title'];
                }
            }
        }

        // ================= Image Upload =================
        $imagePath = null;
        if ($this->isImage && $this->questionImage) {
            $imagePath = $this->questionImage->store('questions', 'public');
        }

        // ================= Save Question =================
        exam_questions::create([
            'exam_id'        => $this->quiz_id,
            'text'           => $this->questionTitle,
            'image'          => $imagePath,
            'options'        => json_encode($options),
            'type'           => $this->questionType,
            'correct_answer' => $correctAnswer,
            'score'          => $this->answerScore,
        ]);

        // ================= Reset =================
        $this->reset([
            'questionTitle',
            'questionType',
            'questionAnswers',
            'correctAnswerIndex',
            'answerScore',
            'isImage',
            'questionImage',
        ]);

        $this->dispatch('message', message: __('Question created successfully!'));
        $this->createQuizQuestions($this->quiz_id);
        $this->switchAction('create-quiz-questions', false, [], []);
    }


    public function addQuestionAnswer()
    {
        if ($this->questionType === 'choose') {

            // شيل أي trueOrFalse لو موجود
            $this->questionAnswers = array_values(
                array_filter($this->questionAnswers, function ($answer) {
                    return ($answer['type'] ?? 'choose') !== 'trueOrFalse';
                })
            );

            // أضف choose جديد
            $this->questionAnswers[] = [
                'id' => count($this->questionAnswers) + 1,
                'title' => '',
                'score' => '',
                'correct' => '',
                'type' => 'choose',
            ];
        }

        if ($this->questionType === 'trueOrFalse') {

            // trueOrFalse لازم يكون عنصر واحد فقط
            $this->questionAnswers = [[
                'id' => 1,
                'title' => '',
                'score' => '',
                'correct' => '',
                'type' => 'trueOrFalse',
            ]];
        }
    }

    public function removeQuestionAnswer($index)
    {
        unset($this->questionAnswers[$index]);
    }

    public function createQuizQuestions($id)
    {
        $this->quiz_id = $id;
        $this->quiz = exam_questions::query()->where('exam_id', $id)->get();
        session(['create-quiz-questions-id' => $id]);
        $this->switchAction('create-quiz-questions', false, [], []);
    } // create Quiz Questions

    public function save()
    {
        $this->validate([
            'quizTitle' => 'required|string|max:255',
            'quizDuration' => 'required|integer|min:1',
            // 'quizQuestionsCount' => 'required|integer|min:1',
            // 'quizTotalScore' => 'required|integer|min:1',
            'teacher_id' => 'required|integer|exists:teachers,id',
        ]);


        exam::query()->updateOrCreate(
            ['id' => $this->quiz_id],
            [
                'title' => $this->quizTitle,
                'duration' => $this->quizDuration,
                'questions_count' => 0,
                'total_score' => 0,
                'teacher_id' => $this->teacher_id,
                'user_id' => Auth::id(),
            ]
        );

        $this->dispatch('message', message: $this->quiz_id ? __('Quiz updated successfully!') : __('Quiz created successfully!'));
        $this->switchAction('show-teacher-quizs', false, ['quizTitle', 'quizDuration', 'quizQuestionsCount', 'quizTotalScore', 'quiz_id', 'quiz'], []);
        $this->showTeacherQuiz($this->teacher_id);
    } //save Quiz Header

    public function back($action, $session, $reset = [])
    {
        $this->switchAction($action, false, $reset, $session);
    } // back Redirect


    public function mount()
    {
        if (session()->has('quiz_teacher_id')) {
            $this->teacher_id = session('quiz_teacher_id');
            $this->showTeacherQuiz($this->teacher_id);
        } else if (session()->has('teacher_id_create_quiz')) {
            $this->teacher_id = session('teacher_id_create_quiz');
            $this->createQuiz($this->teacher_id);
        } else {
            $this->switchAction('show', false, [
                'quizTitle',
                'quizDuration',
                // 'quizQuestionsCount',
                // 'quizTotalScore',
            ], ['quiz_teacher_id', 'teacher_id_create_quiz']);
        }
    } // mount

    public function showTeacherQuiz($id)
    {
        session()->forget('quiz_teacher_id');
        session(['quiz_teacher_id' => $id]);
        $this->teacher_id = $id;
        $this->teacher = Teacher::find($id);
        $this->teacherQuizes = exam::query()->where('teacher_id', $id)->get();;
        $this->switchAction('show-teacher-quizs', false, [], []);
    } // show Teacher Quizes

    public function createQuiz($id)
    {
        $this->teacher_id = $id;
        session(['teacher_id_create_quiz' => $id]);
        $this->switchAction('create-quiz', false, [], []);
    } // create Quiz

    public function editQuiz($id)
    {
        $this->quiz_id = $id;
        $this->quiz = exam::find($id);
        $this->quizTitle = $this->quiz->title;
        $this->quizDuration = $this->quiz->duration;
        $this->switchAction('create-quiz', true, [], []);
    } // edit Quiz

    public function render()
    {
        $quizes = exam::all();
        $teachers = Teacher::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
                $query->orWhere('phone', 'like', '%' . $this->search . '%');
            })
            ->where('state', 1)
            ->where('in_out', 1)
            ->get();
        return view('livewire.admins.quiz.quiz-index', compact('quizes', 'teachers'));
    }
}
