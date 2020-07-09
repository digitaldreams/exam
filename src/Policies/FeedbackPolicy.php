<?php

namespace Exam\Policies;

use Exam\Models\Feedback;
use Illuminate\Auth\Access\HandlesAuthorization;
use Permit\Models\User;

class FeedbackPolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     *
     * @return bool
     */
    public function before(User $user)
    {
        //return true if user has super power
    }

    /**
     * @param User $user
     *
     * @return bool
     */
    public function index(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the Feedback.
     *
     * @param User     $user
     * @param Feedback $feedback
     *
     * @return mixed
     */
    public function view(User $user, Feedback $feedback)
    {
        return true;
    }

    /**
     * Determine whether the user can create Feedback.
     *
     * @param User $user
     *
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the Feedback.
     *
     * @param User     $user
     * @param Feedback $feedback
     *
     * @return mixed
     */
    public function update(User $user, Feedback $feedback)
    {
        return is_object($feedback->feedbackable) && $feedback->feedbackable->user_id == $user->id;
    }

    /**
     * Determine whether the user can delete the Feedback.
     *
     * @param User     $user
     * @param Feedback $feedback
     *
     * @return mixed
     */
    public function delete(User $user, Feedback $feedback)
    {
        return is_object($feedback->feedbackable) && $feedback->feedbackable->user_id == $user->id;
    }
}
