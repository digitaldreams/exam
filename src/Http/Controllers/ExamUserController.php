<?php
/**
 * Created by PhpStorm.
 * User: Tuhin
 * Date: 8/12/2018
 * Time: 3:46 PM.
 */

namespace Exam\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exam\Enums\ExamUserStatus;
use Exam\Enums\ExamVisibility;
use Exam\Http\Requests\Exams\Answer;
use Exam\Http\Requests\Exams\Question;
use Exam\Http\Requests\Exams\Result;
use Exam\Http\Requests\Exams\Start;
use Exam\Http\Requests\Exams\Visibility;
use Exam\Models\Exam;
use Exam\Models\ExamUser;
use Exam\Models\Feedback;
use Exam\Models\Question as QuestionModel;
use Exam\Notifications\ExamCompleted;
use Exam\Services\AnswerService;
use Exam\Services\CertificateService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class ExamUserController extends Controller
{
    /**
     * @param Result $request
     * @param Exam   $exam
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function result(Result $request, ExamUser $exam_user)
    {
        $feedback = Feedback::where('feedbackable_type', get_class($exam_user->exam))
            ->where('feedbackable_id', $exam_user->exam_id)
            ->where('user_id', $exam_user->user_id)
            ->first();
        $exam = $exam_user->exam;
        $answers = \Exam\Models\Answer::whereIn('id', $request->get('answer', []))->get();
        $leftQuestions = [];
        $left = $exam_user->remaining();
        if (false == $left) {
            $exam_user->status = ExamUserStatus::COMPLETED;
            $exam_user->save();
        } else {
            $leftQuestions = $exam->questions()->whereNotIn('id', $exam_user->answers()->pluck('question_id')->toArray())->get();
        }
        if (ExamVisibility::PRIVATE == $exam_user->visibility) {
            $this->authorize('result', $exam_user);
        }

        return view('exam::pages.exams.result', [
            'exam' => $exam,
            'feedback' => $feedback,
            'exam_user' => $exam_user,
            'answers' => $answers,
            'left' => $left,
            'correctionRate' => $exam_user->getCorrectionRate(),
            'obtainMark' => $exam_user->getTotalObtainMark(),
            'totalMark' => $exam->questions()->sum('total_mark'),
            'certificate' => new CertificateService($exam_user),
            'leftQuestions' => $leftQuestions,
        ]);
    }

    /**
     * @param Request $request
     * @param Exam    $exam
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function start(Start $request, Exam $exam)
    {
        $user = auth()->user();
        $examUser = ExamUser::firstOrNew([
            'exam_id' => $exam->id,
            'user_id' => $user->id,
        ]);
        if (ExamUserStatus::COMPLETED == $examUser->status) {
            return redirect()->back()->with('message', 'You already completed this exam');
        }
        if (!$exam->doesCompleteExams()) {
            return redirect()->back()->with('message', 'Please complete all the required exams first');
        }
        $examUser->status = ExamUserStatus::PENDING;
        if (empty($examUser->started_at)) {
            $examUser->started_at = date('Y-m-d H:i:s');
        }
        if ($exam->hasTimeLimit() && $examUser->isTimeOver()) {
            $examUser->status = ExamUserStatus::COMPLETED;
            $examUser->completed_at = date('Y-m-d H:i:s');
            $examUser->save();

            return redirect()->back()->with('permit_error', 'Time over ');
        }
        $examUser->save();

        $question = QuestionModel::forExam($exam->id)
            ->whereNotIn('id', $examUser->getCompleted())
            ->first();

        if (!$question) {
            $examUser->status = ExamUserStatus::COMPLETED;
            $examUser->save();

            return redirect()->back()->with('message', 'You completed all the questions already');
        }

        return redirect()->route('exam::exams.question', [
            'exam' => $exam->slug,
            'question' => $question->id,
        ]);
    }

    /**
     * @param Question $request
     * @param Exam     $exam
     * @param          $qid
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function question(Question $request, Exam $exam, $qid)
    {
        $question = QuestionModel::findOrFail($qid);
        $exam_user = ExamUser::forUser($exam->id)->first();
        $answer = \Exam\Models\Answer::whereIn('id', $request->get('answer', []))->get();
        if (!$exam_user) {
            return redirect()->route('exam::exams.index')->with('permit_error', 'Please choose a exam first');
        }
        $questions = QuestionModel::forExam($exam->id)
            ->whereNotIn('id', $exam_user->getCompleted())
            ->get(['id'])
            ->pluck('id')
            ->toArray();
        $currentQuestionIndex = array_search($question->id, $questions) + 1;

        return view('exam::pages.exams.question', [
            'exam' => $exam,
            'total' => count($questions),
            'position' => $currentQuestionIndex,
            'nextId' => isset($questions[$currentQuestionIndex]) ? $questions[$currentQuestionIndex] : false,
            'previousId' => isset($questions[$currentQuestionIndex - 2]) ? $questions[$currentQuestionIndex - 2] : false,
            'question' => QuestionModel::findOrFail($qid),
            'answers' => $answer,
            'timestamp' => time(),
            'examUser' => $exam_user,
        ]);
    }

    /**
     * @param Answer $request
     * @param Exam   $exam
     * @param        $qid
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function answer(Answer $request, Exam $exam, $qid)
    {
        $nextId = $request->get('nextId', false);
        $exam_user = ExamUser::forUser($exam->id)->first();
        if (!$exam_user) {
            return redirect()->route('exam::exams.index')->with('error', 'Please choose a exam first');
        }
        if ($exam->hasTimeLimit() && $exam_user->isTimeOver()) {
            $exam_user->completed_at = date('Y-m-d H:i:s');
            $exam_user->status = ExamUserStatus::COMPLETED;
            $exam_user->save();

            return redirect()->route('exam::exams.result', ['exam_user' => $exam_user->id])->with('message', 'Time\'s up');
        }

        $answerService = new AnswerService($request->get('answer'), $exam_user);
        $answerService->check();
        $answerIds = $answerService->collection()->pluck('id')->toArray();
        if (!empty($nextId)) {
            return redirect()->route('exam::exams.question', [
                'exam' => $exam->slug,
                'question' => $nextId,
                'answer' => $answerIds,
            ]);
        } else {
            $exam_user->completed_at = date('Y-m-d H:i:s');
            $exam_user->save();
            $certificate = new CertificateService($exam_user);
            $certificate->make();
            Notification::send(User::getAdmins(), new ExamCompleted($exam, auth()->user()));

            return redirect()->route('exam::exams.result', ['exam_user' => $exam_user->id, 'answer' => $answerIds]);
        }
    }

    /**
     * @param Visibility $request
     * @param ExamUser   $exam_user
     * @param            $visibility
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function visibility(Visibility $request, ExamUser $exam_user, $visibility)
    {
        $this->authorize('update', $exam_user->exam);
        $exam_user->visibility = $visibility;
        $exam_user->save();

        return redirect()->back()->with('message', 'Your exam result visibility set to ' . $visibility);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \Exam\Models\Exam        $exam
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function completed(Request $request, Exam $exam)
    {
        $this->authorize('update', $exam);

        return view('exam::pages.exams.completed', [
            'exam' => $exam,
            'completedExams' => $exam->examUser()->latest()->paginate(10),
        ]);
    }
}
