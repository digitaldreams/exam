<?php

namespace Exam\Policies;

use Exam\Models\Invitation;
use Illuminate\Auth\Access\HandlesAuthorization;

class InvitationPolicy
{
    use HandlesAuthorization;

    /**
     * @param  $user
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
    public function index($user)
    {
        return false;
    }

    /**
     * Determine whether the user can view the Invitation.
     *
     * @param        $user
     * @param Invitation $invitation
     *
     * @return mixed
     */
    public function view($user, Invitation $invitation)
    {
        return false;
    }

    /**
     * Determine whether the user can create Invitation.
     *
     * @param  $user
     *
     * @return mixed
     */
    public function create($user)
    {
        return false;
    }

    /**
     * Determine whether the user can update the Invitation.
     *
     * @param       $user
     * @param Invitation $invitation
     *
     * @return mixed
     */
    public function update($user, Invitation $invitation)
    {
        return false;
    }

    /**
     * Determine whether the user can delete the Invitation.
     *
     * @param       $user
     * @param Invitation $invitation
     *
     * @return mixed
     */
    public function delete($user, Invitation $invitation)
    {
        return false;
    }
}
