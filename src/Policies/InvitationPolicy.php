<?php

namespace Exam\Policies;

use Exam\Models\Invitation;
use Illuminate\Auth\Access\HandlesAuthorization;
use Permit\Models\User;

class InvitationPolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     *
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
     *
     * @return bool
     */
    public function index(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can view the Invitation.
     *
     * @param User       $user
     * @param Invitation $invitation
     *
     * @return mixed
     */
    public function view(User $user, Invitation $invitation)
    {
        return false;
    }

    /**
     * Determine whether the user can create Invitation.
     *
     * @param User $user
     *
     * @return mixed
     */
    public function create(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can update the Invitation.
     *
     * @param User       $user
     * @param Invitation $invitation
     *
     * @return mixed
     */
    public function update(User $user, Invitation $invitation)
    {
        return false;
    }

    /**
     * Determine whether the user can delete the Invitation.
     *
     * @param User       $user
     * @param Invitation $invitation
     *
     * @return mixed
     */
    public function delete(User $user, Invitation $invitation)
    {
        return false;
    }
}
