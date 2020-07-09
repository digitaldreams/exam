<?php

namespace Exam\Policies;

use \Exam\Models\Question;
use Illuminate\Auth\Access\HandlesAuthorization;

class QuestionPolicy
{
    use HandlesAuthorization;

    /**
     * @param  $user
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
     * @return bool
     */
    public function index($user)
    {
        return false;
    }

    /**
     * Determine whether the user can view the Question.
     *
     * @param  $user
     * @param  Question $question
     * @return mixed
     */
    public function view($user, Question $question)
    {
        return true;
    }

    /**
     * Determine whether the user can create Question.
     *
     * @param  User $user
     * @return mixed
     */
    public function create($user)
    {
        return false;
    }

    /**
     * Determine whether the user can update the Question.
     *
     * @param  $user
     * @param  Question $question
     * @return mixed
     */
    public function update($user, Question $question)
    {
        return false;
    }

    /**
     * Determine whether the user can delete the Question.
     *
     * @param  $user
     * @param  Question $question
     * @return mixed
     */
    public function delete($user, Question $question)
    {
        return false;
    }

}
