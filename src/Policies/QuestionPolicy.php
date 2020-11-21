<?php

namespace Exam\Policies;

use App\Models\User;
use Exam\Models\Question;
use Illuminate\Auth\Access\HandlesAuthorization;

class QuestionPolicy
{
    use HandlesAuthorization;

    /**
     * @param \App\Models\User $user
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
     * @param \App\Models\User $user
     *
     * @return bool
     */
    public function viewAny(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can view the Question.
     *
     * @param \App\Models\User $user
     * @param Question         $question
     *
     * @return mixed
     */
    public function view($user, Question $question)
    {
        return false;
    }

    /**
     * Determine whether the user can create Question.
     *
     * @param \App\Models\User $user
     *
     * @return mixed
     */
    public function create($user)
    {
        return false;
    }

    /**
     * Determine whether the user can update the Question.
     *
     * @param \App\Models\User $user
     * @param Question         $question
     *
     * @return mixed
     */
    public function update($user, Question $question)
    {
        return $this->isAuthor($user, $question);
    }

    /**
     * Determine whether the user can delete the Question.
     *
     * @param \App\Models\User $user
     * @param Question         $question
     *
     * @return mixed
     */
    public function delete($user, Question $question)
    {
        return $this->isAuthor($user, $question);
    }

    /**
     * @param                       $user
     * @param \Exam\Models\Question $question
     *
     * @return bool
     */
    public function isAuthor($user, Question $question)
    {
        return $user->id === $question->user_id;
    }
}
