<?php

namespace Exam\Services;

use App\Models\User;
use Exam\Enums\ExamUserStatus;
use Exam\Models\Exam;
use Exam\Models\Question;
use Exam\Repositories\AnswerRepository;
use Exam\Repositories\ExamRepository;
use Exam\Repositories\ExamUserRepository;
use Exam\Repositories\FeedbackRepository;
use Exam\Repositories\QuestionRepository;

class TakeExamService
{
    /**
     * @var \Exam\Repositories\ExamUserRepository
     */
    protected $examUserRepository;

    /**
     * @var \Exam\Repositories\QuestionRepository
     */
    protected $questionRepository;

    /**
     * @var \Exam\Repositories\FeedbackRepository
     */
    protected $feedbackRepository;

    /**
     * @var \Exam\Models\Question
     */
    protected $currentQuestion;

    /**
     * @var \App\Models\User|null
     */
    protected $user;

    /**
     * @var string
     */
    protected $token;

    /**
     * @var string
     */
    protected $ip;

    /**
     * @var \Exam\Models\ExamUser
     */
    protected $examUser;

    /**
     * @var \Exam\Models\Exam
     */
    protected $exam;
    /**
     * @var \Exam\Repositories\ExamRepository
     */
    protected $examRepository;

    /**
     * @var \Illuminate\Database\Eloquent\Collection
     */
    protected $questions;
    /**
     * @var \Exam\Repositories\AnswerRepository
     */
    protected $answerRepository;

    /**
     * TakeExamService constructor.
     *
     * @param \Exam\Repositories\ExamRepository     $examRepository
     * @param \Exam\Repositories\ExamUserRepository $examUserRepository
     * @param \Exam\Repositories\QuestionRepository $questionRepository
     * @param \Exam\Repositories\FeedbackRepository $feedbackRepository
     * @param \Exam\Repositories\AnswerRepository   $answerRepository
     */
    public function __construct(ExamRepository $examRepository, ExamUserRepository $examUserRepository, QuestionRepository $questionRepository, FeedbackRepository $feedbackRepository, AnswerRepository $answerRepository)
    {
        $this->examUserRepository = $examUserRepository;
        $this->questionRepository = $questionRepository;
        $this->feedbackRepository = $feedbackRepository;
        $this->examRepository = $examRepository;
        $this->answerRepository = $answerRepository;
    }

    /**
     * @param \App\Models\User $user
     *
     * @return $this
     */
    public function asUser(User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @param string $token
     * @param string $ip
     *
     * @return \Exam\Services\TakeExamService
     */
    public function asGuest(string $token, string $ip)
    {
        $this->token = $token;
        $this->ip = $ip;

        return $this;
    }

    /**
     * @param \Exam\Models\Exam $exam
     *
     * @return \Exam\Models\ExamUser|null
     *
     * @throws \Exception
     */
    public function start()
    {
        $this->examUser = !empty($this->examUser) ? $this->examUser : $this->getExamUserModel();

        if (!$this->examUser) {
            $this->examUser = $this->createExamUser();
        }

        return $this->examUser;
    }

    /**
     * @param \Exam\Models\Exam $exam
     *
     * @return $this
     */
    public function setExam(Exam $exam)
    {
        $this->exam = $exam;

        return $this;
    }

    /**
     * @param \Exam\Models\Question $question
     *
     * @return $this
     */
    public function setCurrentQuestion(Question $question)
    {
        $this->currentQuestion = $question;

        return $this;
    }

    public function getQuestions()
    {
        return $this->questions = $this->questionRepository->getRemainingQuestions($this->examUser);
    }

    /**
     * @param array $ids
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAnswers(array $ids)
    {
        return $this->answerRepository->getByIds($ids);
    }

    /**
     * @return string
     */
    public function timeLeft()
    {
        return $this->examUserRepository->timeLeft($this->examUser);
    }


    /**
     * @return \Exam\Models\ExamUser|null
     */
    private function getExamUserModel()
    {
        if ($this->user) {
            return $this->examUserRepository->getByUser($this->exam, $this->user);
        }

        return $this->examUserRepository->getByToken($this->exam, $this->token);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Model
     *
     * @throws \Exception
     */
    private function createExamUser()
    {
        $data = [];
        if ($this->user) {
            $data['user_id'] = $this->user->id;
        } else {
            $data['token'] = $this->token;
            $data['ip'] = $this->ip;
        }
        $data['exam_id'] = $this->exam->id;
        $data['status'] = ExamUserStatus::PENDING;
        $data['started_at'] = (new \DateTime())->format('Y-m-d H:i:s');

        return $this->examUserRepository->create($data);
    }

    /**
     * @return bool
     */
    public function isTimeOver()
    {
        if ($this->examUserRepository->isTimeOver($this->examUser)) {
            if (ExamUserStatus::COMPLETED !== $this->examUser->status) {
                $data['status'] = ExamUserStatus::COMPLETED;
                $data['completed_at'] = date('Y-m-d H:i:s');
                $this->examUserRepository->update($data, $this->examUser);

                return true;
            }

            return false;
        }
    }

    /**
     * @return bool
     */
    public function isRequiredExamCompleted(): bool
    {
        return $this->examRepository->isRequiredExamCompleted($this->exam, $this->user->id);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function markAsCompleted()
    {
        return $this->examUserRepository->update([
            'status' => ExamUserStatus::COMPLETED,
            'completed_at' => date('Y-m-d H:i:s'),
        ], $this->examUser);
    }
}
