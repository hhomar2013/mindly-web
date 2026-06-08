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

        $user = Auth::user()->id;
        $check =  $this->checkIfStudentJoinedQuiz($exam->id, $user);

        if ($check) {
            return response()->json([
                'message' => __('You have already joined this quiz'),
            ], 422);
        }

        return response()->json([
            'message' => __('Quiz instructions'),
            'data' => [
                'title' => $exam->title,
                'duration' => $exam->duration,
                'questions_count' => $exam->questions->count(),
                'total_degrees' => $exam->questions->sum('score'),
                'instructions' => "If you exit the application for any reason the quiz will be submitted automatically",
            ]
        ], 200);
    }


    private function checkIfStudentJoinedQuiz($id, $user)
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
    } //checkIfStudentJoinedQuiz


    public function joinQuiz(Request $request)
    {
        $request->validate([
            'id' => 'required|numeric',
        ]);
        $id = $request->id;
        $user = $request->user()->id;

        $getQuiz = $this->getQuiz($id);
        if (!$getQuiz) {
            return response()->json([
                'message' => __('Quiz not found'),
            ], 404);
        }

        $check =  $this->checkIfStudentJoinedQuiz($id, $user);
        if ($check) {
            return response()->json([
                'message' => __('You have already joined this quiz'),
            ], 422);
        }

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
    } //joinQuiz

    private function getQuiz($id)
    {
        return exam::query()->with('questions')->find($id);
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
        return exam_questions::query()->where('exam_id', $examId)->sum('score');
    }
    public function closeQuiz(Request $request)
    {
        $user = $request->user()->id;

        $firstAnswer = collect($request->all())->first();
        if (!$firstAnswer) {
            return response()->json([
                'message' => __('No answers were provided'),
            ], 400);
        }

        $firstQuestion = exam_questions::query()->find($firstAnswer['question_id']);
        if (!$firstQuestion) {
            return response()->json([
                'message' => __('Invalid question ID'),
            ], 404);
        }
        $exam_id = $firstQuestion->exam_id;

        $studentExam = student_exam::query()
            ->where('exam_id', $exam_id)
            ->where('student_id', $user)
            ->where('state', true)
            ->first();

        if (!$studentExam) {
            return response()->json([
                'message' => __('You have not joined this quiz, or it has already been submitted'),
            ], 422);
        }

        $studentScore = 0;
        foreach ($request->all() as $key => $val) {
            $questionId = $val['question_id'];
            $answer = $val['answer'];

            $q = exam_questions::query()->find($questionId);
            if ($q) {
                $correctAnswer = $q->correct_answer;

                if ($correctAnswer == $answer) {
                    $score = $q->score;
                    $studentScore += $score;
                    $this->answer($studentExam->id, $answer, 0, $score);
                } else {
                    $this->answer($studentExam->id, $answer, $correctAnswer, 0);
                }
            }
        }

        $studentExam->update([
            'score' => $studentScore,
            'state' => false
        ]);

        return response()->json([
            'message' => __('Quiz answers saved successfully'),
            'data' => [
                'result' => $studentScore,
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
