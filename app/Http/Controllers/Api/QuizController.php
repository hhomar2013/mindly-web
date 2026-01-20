<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\exam;
use App\Models\exam_questions;
use App\Models\student_exam;
use App\Models\student_exam_answer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{


    public function instructions($id)
    {
        $exam = exam::query()->with('questions')->find($id);
        if (!$exam) {
            return response()->json([
                'message' => __('Quiz not found'),
            ], 404);
        }
        return response()->json([
            'message' => __('Quiz instructions'),
            'data' => $exam ? [
                'title' => $exam->title,
                'duration' => $exam->duration,
                'questions_count' => $exam->questions->count(),
                'total_degrees' => $exam->questions->sum('score'),
                'instructions' => "If you exit the application for any reason the quiz will be submitted automatically",
            ] : []
        ], 200);
    }


    private function checkIfStudentJoiendQuiz($id, $user)
    {
        $studentExam = student_exam::query()
            ->where('exam_id', $id)
            ->where('student_id', $user)
            ->where('state', true)
            ->first();
        if ($studentExam) {
            return $studentExam;
        }
        return false;
    } //checkIfStudentJoiendQuiz


    public function joinQuiz(Request $request)
    {
        $request->validate([
            'id' => 'required|numeric',
        ]);
        $id = $request->id;
        $user = $request->user()->id;
        $check =  $this->checkIfStudentJoiendQuiz($id, $user);
        $getQuiz = $this->getQuiz($id);
        if ($check) {
            return response()->json([
                'message' => __('You have already joined this quiz'),
                'data' =>  ["quiz" =>   $getQuiz, "joinQuiz" => $check]
            ], 200);
        }

        if ($getQuiz) {
            $joinQuiz = student_exam::query()->create([
                'exam_id' => $getQuiz->id,
                'student_id' => $user,
                'state' => true
            ]);


            $data = ['quiz' => $getQuiz, 'joinQuiz' => $joinQuiz];
            return response()->json([
                'message' => __('You have been successfully registered for the quiz'),
                'data' =>  $data
            ], 200);
        }
    } //joinQuiz

    private function getQuiz($id)
    {
        $quiz = exam::query()->where('id', $id)->with('questions')->first();
        if ($quiz) {
            return $quiz;
        }
        return false;
    }


    private function answer($id, $answer, $correctAnswer = 0, $score = 0)
    {
        $saveAnswer = student_exam_answer::query()->create([
            'student_exam_id' => $id,
            'student_answer' => $answer,
            'correct_answer' => $correctAnswer,
            'score' => $score,
        ]);
        return $saveAnswer;
    }
    public function sumQuizAnswers($examId)
    {
        $q = exam_questions::query()->where('exam_id', $examId)->get();
        $sum = 0;
        foreach ($q as $key => $val) {
            $sum += $val->score;
        }
        return $sum;
    }
    public function closeQuiz(Request $request)
    {
        foreach ($request->all() as $key => $val) {
            $questionId = $val['question_id'];
            $answer = $val['answer'];
            $q = exam_questions::query()->find($questionId);
            $exam_id = $q->exam_id;
            if ($q) {
                $correctAnswer = $q->correct_answer;
                if ($correctAnswer == $answer) {
                    $this->answer($exam_id, $answer, 0, $q->score);
                } else {
                    $this->answer($exam_id, $answer, $correctAnswer, 0);
                }
            }
        }

        $sum = $this->sumQuizAnswers($exam_id);
        return response()->json([
            'message' => __('Quiz answers saved successfully'),
            'data' => [
                'result' => $sum,
            ]
        ], 200);
    }


    public function index()
    {
        return response()->json([
            'message' => 'Welcome in Quiz section ✔️',
            'data' => []
        ], 200);
    } //index


}
