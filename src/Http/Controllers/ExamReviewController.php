<?php

namespace Exam\Http\Controllers;

use App\Http\Controllers\Controller;
use Exam\Enums\AnswerStatus;
use Exam\Enums\QuestionReview;
use Exam\Http\Requests\Answers\Index;
use Exam\Http\Requests\Answers\Show;
use Exam\Http\Requests\Answers\Update;
use Exam\Models\Answer;
use Exam\Models\Exam;
use Exam\Models\ExamUser;
use Exam\Notifications\ReviewCompletedNotification;
use Illuminate\Translation\Translator;

class ExamReviewController extends Controller
{
    /**
     * @var \Illuminate\Translation\Translator
     */
    protected $translator;

    /**
     * ExamReviewController constructor.
     *
     * @param \Illuminate\Translation\Translator $translator
     */
    public function __construct(Translator $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @param Index $index
     * @param Exam  $exam
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Exam $exam)
    {
        $examUsers = ExamUser::query()->where('exam_id', $exam->id)
            ->whereHas('exam.questions', function ($q) {
                $q->where('review_type', QuestionReview::MANUAL)
                    ->whereHas('answer', function ($aq) {
                        $aq->where('status', AnswerStatus::PENDING);
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
    public function show(Exam $exam, Answer $answer)
    {
        return view('exam::pages.reviews.show', [
            'record' => $answer->question,
            'exam' => $exam,
            'answer' => $answer,
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
        $answer->fill($request->all())->save();

        $answer->examUser->user->notify(new ReviewCompletedNotification($answer, $exam));

        return redirect()
            ->route('exam::exams.reviews.index', $exam->slug)
            ->with('message', $this->translator->get('exam::flash.answer.reviewed'));
    }
}
