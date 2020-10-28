<?php

namespace Exam\Policies;

use App\Models\User;
use Exam\Enums\ExamUserStatus;
use Exam\Enums\ExamVisibility;
use Exam\Models\Exam;
use Exam\Models\Invitation;
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
        return false;
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
        return $this->isAuthor($user, $exam) || $this->isInvited($user, $exam);
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
     * @param \App\Models\User $user
     * @param Exam             $exam
     *
     * @return mixed
     */
    public function update($user, Exam $exam)
    {
        return $this->isAuthor($user, $exam);
    }

    /**
     * Determine whether the user can delete the Exam.
     *
     * @param      $user
     * @param Exam $exam
     *
     * @return mixed
     */
    public function delete($user, Exam $exam)
    {
        return $this->isAuthor($user, $exam);
    }

    /**
     * @param \App\Models\User  $user
     * @param \Exam\Models\Exam $exam
     *
     * @return bool
     */
    public function start(?User $user, Exam $exam)
    {
        if ($this->isPublic($exam)) {
            return true;
        }
        // A guest can not able to take Private Exam.
        if (empty($user)) {
            return false;
        }

        if (!$this->isNotCompletedYet($user, $exam)) {
            return true;
        }

        return $this->isInvited($user, $exam);
    }

    /**
     * Determine whether the user can delete the Exam.
     *
     * @param      $user
     * @param Exam $exam
     *
     * @return mixed
     */
    public function answer($user, Exam $exam)
    {
        return $this->isAuthor($user, $exam);
    }

    /**
     * @param \App\Models\User  $user
     * @param \Exam\Models\Exam $exam
     *
     * @return bool
     */
    public function isAuthor($user, Exam $exam): bool
    {
        return $user->id === $exam->user_id;
    }

    /**
     * @param                   $user
     * @param \Exam\Models\Exam $exam
     *
     * @return bool
     */
    public function isInvited($user, Exam $exam)
    {
        return $exam->invitations()->where('user_id', $user->id)
            ->where('status', Invitation::STATUS_ACCEPTED)
            ->exists();
    }

    /**
     * @param \App\Models\User|null $user
     * @param \Exam\Models\Exam     $exam
     *
     * @return bool
     */
    public function open(?User $user, Exam $exam)
    {
        return $this->isPublic($exam);
    }

    /**
     * @param \Exam\Models\Exam $exam
     *
     * @return bool
     */
    protected function isPublic(Exam $exam): bool
    {
        return ExamVisibility::PUBLIC === $exam->visibility;
    }

    /**
     * @param                   $user
     * @param \Exam\Models\Exam $exam
     *
     * @return bool
     */
    protected function isNotCompletedYet($user, Exam $exam)
    {
        return !$exam->examUsers()
            ->where('user_id', $user->id)
            ->where('status', ExamUserStatus::COMPLETED)
            ->exists();
    }
}
