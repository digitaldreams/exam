<?php

namespace Exam\Policies;

use \Exam\Models\Exam;
use Exam\Models\ExamUser;
use Permit\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ExamUserPolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     * @return bool
     */
    public function before(User $user)
    {
        if (method_exists($user, 'isSuperAdmin') && $user->isSuperAdmin()) {
            return true;
        }
    }

    /**
     * @param User $user
     * @return bool
     */
    public function index(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the Exam.
     *
     * @param  User $user
     * @param ExamUser $examUser
     * @return mixed
     */
    public function view(User $user, ExamUser $examUser)
    {
        return true;
    }

    /**
     * Determine whether the user can create Exam.
     *
     * @param  User $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the Exam.
     *
     * @param User $user
     * @param ExamUser $examUser
     * @return mixed
     */
    public function update(User $user, ExamUser $examUser)
    {
        return $user->id == $examUser->user_id;
    }

    /**
     * Determine whether the user can delete the Exam.
     *
     * @param User $user
     * @param ExamUser $examUser
     * @return mixed
     */
    public function delete(User $user, ExamUser $examUser)
    {
        return $user->id == $examUser->user_id;
    }

    /**
     * Determine whether the user can delete the Exam.
     *
     * @param User $user
     * @param ExamUser $examUser
     * @return mixed
     */
    public function answer(User $user, ExamUser $examUser)
    {
        return $user->id == $examUser->user_id;
    }

    /**
     * Determine whether the user can delete the Exam.
     *
     * @param User $user
     * @param ExamUser $examUser
     * @return mixed
     */
    public function result(User $user, ExamUser $examUser)
    {
        return $user->id == $examUser->user_id || $examUser->visibility == ExamUser::VISIBILITY_PUBLIC;
    }


}
