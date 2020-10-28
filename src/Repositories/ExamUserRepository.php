<?php


namespace Exam\Repositories;


use App\Models\User;
use Carbon\Carbon;
use Exam\Models\Exam;
use Exam\Models\ExamUser;

class ExamUserRepository extends Repository
{
    /**
     * ExamUserRepository constructor.
     *
     * @param \Exam\Models\ExamUser $examUser
     */
    public function __construct(ExamUser $examUser)
    {
        $this->model = $examUser;
    }

    /**
     * @param \Exam\Models\Exam $exam
     * @param \App\Models\User  $user
     *
     * @return \Exam\Models\ExamUser|null
     */
    public function getByUser(Exam $exam, User $user)
    {
        return $this->model->newQuery()->where('exam_id', $exam->id)->where('user_id', $user->id)->first();
    }

    /**
     * @param \Exam\Models\Exam $exam
     * @param string            $token
     *
     * @return \Exam\Models\ExamUser|null
     */
    public function getByToken(Exam $exam, string $token)
    {
        return $this->model->newQuery()->where('exam_id', $exam->id)->where('token', $token)->first();
    }

    /**
     * @param \Exam\Models\ExamUser $examUser
     *
     * @return bool
     */
    public function isTimeOver(ExamUser $examUser)
    {
        if ($examUser->exam->hasTimeLimit()) {
            $lastTime = Carbon::parse($examUser->started_at)->addMinutes($examUser->exam->duration);

            return $lastTime->lt(Carbon::now());
        }

        return false;
    }


    /**
     * @param \Exam\Models\ExamUser $examUser
     *
     * @return string
     */
    public function timeLeft(ExamUser $examUser)
    {
        if (!empty($examUser->started_at)) {
            $lastTime = Carbon::parse($examUser->started_at)->addMinutes($examUser->exam->duration);

            return $lastTime->diff(Carbon::now())->format('%H:%I:%S');
        }

        return '';
    }

}
