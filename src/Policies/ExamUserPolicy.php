<?php

namespace Exam\Policies;

use App\Models\User;
use Exam\Models\ExamUser;
use Illuminate\Auth\Access\HandlesAuthorization;

class ExamUserPolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
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
     * @param User $user
     *
     * @return bool
     */
    public function index($user)
    {
        return false;
    }

    /**
     * Determine whether the user can view the Exam.
     *
     * @param User     $user
     * @param ExamUser $examUser
     *
     * @return mixed
     */
    public function view($user, ExamUser $examUser)
    {
        return $this->isCandidate($user, $examUser);
    }

    /**
     * Determine whether the user can create Exam.
     *
     * @param User $user
     *
     * @return mixed
     */
    public function create($user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the Exam.
     *
     * @param User     $user
     * @param ExamUser $examUser
     *
     * @return mixed
     */
    public function update($user, ExamUser $examUser)
    {
        return $this->isCandidate($user, $examUser);
    }

    /**
     * Determine whether the user can delete the Exam.
     *
     * @param User     $user
     * @param ExamUser $examUser
     *
     * @return mixed
     */
    public function delete($user, ExamUser $examUser)
    {
        return $this->isCandidate($user, $examUser);
    }

    /**
     * Determine whether the user can delete the Exam.
     *
     * @param User     $user
     * @param ExamUser $examUser
     *
     * @return mixed
     */
    public function answer($user, ExamUser $examUser)
    {
        return $this->isCandidate($user, $examUser);
    }

    /**
     * Determine whether the user can delete the Exam.
     *
     * @param User     $user
     * @param ExamUser $examUser
     *
     * @return mixed
     */
    public function result($user, ExamUser $examUser)
    {
        return $user->id == $examUser->user_id || ExamUser::VISIBILITY_PUBLIC == $examUser->visibility;
    }

    /**
     * @param \App\Models\User      $user
     * @param \Exam\Models\ExamUser $examUser
     *
     * @return bool
     */
    public function isCandidate($user, ExamUser $examUser)
    {
        return $user->id == $examUser->user_id;
    }
}
