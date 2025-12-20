<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\exam;
use App\Models\student_exam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{

    public function joinQuiz(Request $request)
    {
        $request->validate([
            'id' => 'required',
        ]);
        $quiz = exam::query()->find($request->id);
        if ($quiz) {
            $joinQuiz = student_exam::query()->create([
                'exam_id' => $quiz->id,
                'student_id' => Auth::user()->id,
            ]);

            return response()->json([
                'message' => __('You have been successfully registered for the quiz'),
                'data' =>  $joinQuiz
            ], 200);
        }
    }

    public function index()
    {
        return response()->json([
            'message' => 'Welcome in Quiz section ✔️',
            'data' => []
        ], 200);
    }
}
