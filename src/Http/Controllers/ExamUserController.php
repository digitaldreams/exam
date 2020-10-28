<?php

namespace Exam\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exam\Enums\ExamUserStatus;
use Exam\Enums\ExamVisibility;
use Exam\Enums\QuestionReview;
use Exam\Http\Requests\Exams\Answer;
use Exam\Http\Requests\Exams\Result;
use Exam\Http\Requests\Exams\Visibility;
use Exam\Models\Exam;
use Exam\Models\ExamUser;
use Exam\Models\Question;
use Exam\Notifications\ExamCompleted;
use Exam\Notifications\ReviewRequestToTeacher;
use Exam\Repositories\AnswerRepository;
use Exam\Repositories\ExamUserRepository;
use Exam\Repositories\FeedbackRepository;
use Exam\Services\AnswerService;
use Exam\Services\CertificateService;
use Exam\Services\TakeExamService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class ExamUserController extends Controller
{
    /**
     * @var \Exam\Repositories\ExamUserRepository
     */
    protected $examUserRepository;
    /**
     * @var \Exam\Repositories\FeedbackRepository
     */
    protected $feedbackRepository;
    /**
     * @var \Exam\Repositories\AnswerRepository
     */
    protected $answerRepository;
    /**
     * @var \Exam\Services\TakeExamService
     */
    protected $takeExamService;

    /**
     * ExamUserController constructor.
     *
     * @param \Exam\Services\TakeExamService        $takeExamService
     * @param \Exam\Repositories\ExamUserRepository $examUserRepository
     * @param \Exam\Repositories\FeedbackRepository $feedbackRepository
     * @param \Exam\Repositories\AnswerRepository   $answerRepository
     */
    public function __construct(TakeExamService $takeExamService, ExamUserRepository $examUserRepository, FeedbackRepository $feedbackRepository, AnswerRepository $answerRepository)
    {
        $this->examUserRepository = $examUserRepository;
        $this->feedbackRepository = $feedbackRepository;
        $this->answerRepository = $answerRepository;
        $this->takeExamService = $takeExamService;
    }

    /**
     * @param Result $request
     * @param Exam   $exam
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function result(Request $request, ExamUser $examUser)
    {
        $feedback = $this->feedbackRepository->userExamFeedback($examUser->exam_id, $examUser->user_id);

        $exam = $examUser->exam;
        $answers = $this->answerRepository->getByIds($request->get('answer', []));
        $leftQuestions = [];
        $left = $examUser->remaining();
        if (false == $left) {
            $examUser->status = ExamUserStatus::COMPLETED;
            $examUser->save();
        } else {
            $leftQuestions = $exam->questions()->whereNotIn('id', $examUser->answers()->pluck('question_id')->toArray())->get();
        }
        if (ExamVisibility::PRIVATE == $examUser->visibility) {
            $this->authorize('result', $examUser);
        }

        return view('exam::pages.exams.result', [
            'exam' => $exam,
            'feedback' => $feedback,
            'exam_user' => $examUser,
            'answers' => $answers,
            'left' => $left,
            'correctionRate' => $examUser->getCorrectionRate(),
            'obtainMark' => $examUser->getTotalObtainMark(),
            'totalMark' => $exam->questions()->sum('total_mark'),
            'certificate' => new CertificateService($examUser),
            'leftQuestions' => $leftQuestions,
        ]);
    }

    /**
     * @param Request $request
     * @param Exam    $exam
     *
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Exception
     */
    public function start(Request $request, Exam $exam)
    {
        $this->authorize('start', $exam);

        $this->takeExamService->setExam($exam);

        if (auth()->check()) {
            $this->takeExamService->asUser(auth()->user());
            if (!$this->takeExamService->isRequiredExamCompleted()) {
                return redirect()->back()->with('message', 'Please complete all the required exams first');
            }
        } else {
            $token = Str::random(32);
            $this->takeExamService->asGuest($token, $request->ip());
            $request->session()->put('exam_token', $token);
        }
        $this->takeExamService->start();
        if ($this->takeExamService->isTimeOver()) {
            return redirect()->back()->with('error', 'Time over ');
        }

        $question = $this->takeExamService->getQuestions()->first();

        if (!$question) {
            return redirect()->back()->with('message', 'You completed all the questions already');
        }

        return redirect()->route('exam::exams.question', [
            'exam' => $exam->slug,
            'question' => $question->id,
        ]);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param Exam                     $exam
     * @param \Exam\Models\Question    $question
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Exception
     */
    public function question(Request $request, Exam $exam, Question $question)
    {
        $this->authorize('start', $exam);
        $this->takeExamService->setExam($exam);

        if (auth()->check()) {
            $this->takeExamService->asUser(auth()->user());
        } else {
            $this->takeExamService->asGuest($request->session()->get('exam_token'), $request->ip());
        }

        $examUser = $this->takeExamService->start();

        if (!$examUser) {
            return redirect()->route('exam::exams.index')->with('error', 'Please choose a exam first');
        }

        $questions = $this->takeExamService->getQuestions()
            ->pluck('id')
            ->toArray();
        $currentQuestionIndex = array_search($question->id, $questions) + 1;

        return view('exam::pages.exams.question', [
            'exam' => $exam,
            'total' => count($questions),
            'position' => $currentQuestionIndex,
            'nextId' => isset($questions[$currentQuestionIndex]) ? $questions[$currentQuestionIndex] : false,
            'previousId' => isset($questions[$currentQuestionIndex - 2]) ? $questions[$currentQuestionIndex - 2] : false,
            'question' => $question,
            'answers' => $this->takeExamService->getAnswers($request->get('answer', [])),
            'timestamp' => time(),
            'examUser' => $examUser,
            'takeExamService' => $this->takeExamService,
        ]);
    }

    /**
     * @param Answer                $request
     * @param Exam                  $exam
     * @param \Exam\Models\Question $question
     *
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function answer(Answer $request, Exam $exam, Question $question)
    {
        $this->authorize('start', $exam);
        $this->takeExamService->setExam($exam);

        $nextId = $request->get('nextId', false);

        if (auth()->check()) {
            $this->takeExamService->asUser(auth()->user());
        } else {
            $this->takeExamService->asGuest($request->session()->get('exam_token'), $request->ip());
        }
        $examUser = $this->takeExamService->start();

        if (!$examUser) {
            return redirect()->route('exam::exams.index')->with('error', 'Please choose a exam first');
        }

        if ($this->takeExamService->isTimeOver()) {
            return redirect()->route('exam::exams.result', ['exam_user' => $examUser->id])->with('message', 'Time\'s up');
        }

        $answerService = new AnswerService($request->get('answer'), $examUser);
        $answerService->check();
        $answerIds = $answerService->collection()->pluck('id')->toArray();

        $questions = $this->takeExamService->getQuestions();

        if (!empty($nextId)) {
            return redirect()->route('exam::exams.question', [
                'exam' => $exam->slug,
                'question' => $nextId,
                'answer' => $answerIds,
            ]);
        } elseif (count($questions) > 0) {
            return redirect()->route('exam::exams.question', [
                'exam' => $exam->slug,
                'question' => $questions->shift()->id ?? null,
                'answer' => $answerIds,
            ]);
        } else {
            $this->takeExamService->markAsCompleted();
            if (auth()->check()) {
                $certificate = new CertificateService($examUser);
                $certificate->make();
                Notification::send(User::getAdmins(), new ExamCompleted($exam, $examUser->user));

                $totalPendingQuestion = $examUser->exam->questions()->where('review_type', QuestionReview::MANUAL)->count();
                if ($totalPendingQuestion > 0) {
                    Notification::send(User::getAdmins(), new ReviewRequestToTeacher($examUser));
                }
            }
            if (auth()->check()) {
                return redirect()->route('exam::exams.result', ['exam_user' => $examUser->id, 'answer' => $answerIds]);
            } else {
                return redirect()->route('register', [
                    'returnUrl' => route('exam::exams.assignUser', ['examUser' => $examUser->id, 'token' => $this->takeExamService->getToken()]),
                ])->with('message', 'Please sign up to view your result.');
            }
        }
    }

    /**
     * @param Visibility $request
     * @param ExamUser   $exam_user
     * @param            $visibility
     *
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
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
     * @param \Exam\Models\ExamUser    $examUser
     * @param string                   $token
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function assignUser(Request $request, ExamUser $examUser, string $token)
    {
        if ($request->session()->has('exam_token') && $request->session()->pull('exam_token') == $token) {
            $this->examUserRepository->update([
                'user_id' => auth()->id(),
                'token' => null,
            ], $examUser);

            return redirect()->route('exam::exams.result', ['exam_user' => $examUser->id, 'answer' => []]);
        } else {
            return redirect()->route('exam::exams.index')->with('error', 'Your token does not seems to be valid');
        }
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \Exam\Models\Exam        $exam
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function completed(Request $request, Exam $exam)
    {
        $this->authorize('update', $exam);

        return view('exam::pages.exams.completed', [
            'exam' => $exam,
            'completedExams' => $exam->examUsers()->latest()->paginate(10),
        ]);
    }
}
