<?php

namespace Exam\Http\Controllers;

use App\Http\Controllers\Controller;
use Exam\Http\Requests\Answers\Index;
use Exam\Http\Requests\Answers\Show;
use Exam\Http\Requests\Answers\Update;
use Exam\Models\Answer;
use Exam\Models\Exam;
use Exam\Models\ExamUser;
use Exam\Models\Question;
use Exam\Notifications\ReviewCompletedNotification;

class ExamReviewController extends Controller
{
    /**
     * @param Index $index
     * @param Exam  $exam
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Index $index, Exam $exam)
    {
        $examUsers = ExamUser::where('exam_id', $exam->id)->whereHas('exam.questions', function ($q) {
            $q->where('review_type', Question::REVIEW_TYPE_MANUAL)
                ->whereHas('answer', function ($aq) {
                    $aq->where('status', Answer::STATUS_PENDING);
                });
        })->paginate(6);

        return view('exam::pages.reviews.index', [
            'records' => $examUsers,
            'exam' => $exam,
        ]);
    }

    /**
     * @param Show   $show
     * @param Answer $answer
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Show $show, Answer $answer)
    {
        return view('exam::pages.reviews.show', [
            'record' => $answer,
        ]);
    }

    /**
     * @param Update $request
     * @param Exam   $exam
     * @param Answer $answer
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Update $request, Exam $exam, Answer $answer)
    {
        $answer->status = $request->get('status');
        if ($answer->save()) {
            $answer->examUser->user->notify(new ReviewCompletedNotification($answer, $exam));
            session()->flash('permit_message', 'Answer successfully reviewed');

            return redirect()->route('exam::exams.reviews.index', $exam->slug);
        } else {
            session()->flash('permit_error', 'Something is wrong while reviewing answer');
        }

        return redirect()->back();
    }
}
