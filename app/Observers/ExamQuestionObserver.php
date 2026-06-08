<?php

namespace App\Observers;

use App\Models\exam;
use App\Models\exam_questions;

class ExamQuestionObserver
{
    /**
     * Handle the exam_questions "created" event.
     */
    public function created(exam_questions $question): void
    {
        $exam = exam::query()->find($question->exam_id);
        if ($exam) {
            $exam->increment('questions_count');
            $exam->increment('total_score', $question->score);
        }
    }

    /**
     * Handle the exam_questions "updated" event.
     */
    public function updated(exam_questions $question): void
    {
        if ($question->isDirty('score')) {
            $exam = exam::query()->find($question->exam_id);
            if ($exam) {
                $difference = $question->score - $question->getOriginal('score');
                $exam->increment('total_score', $difference);
            }
        }
    }

    /**
     * Handle the exam_questions "deleted" event.
     */
    public function deleted(exam_questions $question): void
    {
        $exam = exam::query()->find($question->exam_id);
        if ($exam) {
            $exam->decrement('questions_count');
            $exam->decrement('total_score', $question->score);
        }
    }
}
