<?php

namespace Exam\Policies;

use Exam\Enums\ExamUserStatus;
use Exam\Models\Exam;
use Exam\Models\ExamUser;
use Illuminate\Auth\Access\HandlesAuthorization;

class ExamPolicy
{
    use HandlesAuthorization;

    /**
     * @param $user
     *
     * @return bool
     */
    public function before($user)
    {
        if (method_exists($user, 'isAdmin') && $user->isAdmin()) {
            return true;
        }
    }

    /**
     * @param  $user
     *
     * @return bool
     */
    public function viewAny($user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the Exam.
     *
     * @param      $user
     * @param Exam $exam
     *
     * @return mixed
     */
    public function view($user, Exam $exam)
    {
        return true;
    }

    /**
     * Determine whether the user can create Exam.
     *
     * @param $user
     *
     * @return mixed
     */
    public function create($user)
    {
        return false;
    }

    /**
     * Determine whether the user can update the Exam.
     *
     * @param User $user
     * @param Exam $exam
     *
     * @return mixed
     */
    public function update($user, Exam $exam)
    {
        return false;
    }

    /**
     * Determine whether the user can delete the Exam.
     *
     * @param  $user
     * @param Exam $exam
     *
     * @return mixed
     */
    public function delete($user, Exam $exam)
    {
        return false;
    }

    /**
     * @param                   $user
     * @param \Exam\Models\Exam $exam
     *
     * @return bool
     */
    public function start($user, Exam $exam)
    {
        $examUser = ExamUser::where('exam_id', $exam->id)
            ->where('user_id', $user->id)
            ->first();
        if ($examUser) {
            return ExamUserStatus::COMPLETED !== $examUser->status;
        }

        return true;
    }

    /**
     * Determine whether the user can delete the Exam.
     *
     * @param  $user
     * @param Exam $exam
     *
     * @return mixed
     */
    public function answer($user, Exam $exam)
    {
        return false;
    }
}
