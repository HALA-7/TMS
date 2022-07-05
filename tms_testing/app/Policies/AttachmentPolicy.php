<?php

namespace App\Policies;

use App\Models\Attachment;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AttachmentPolicy
{
    use HandlesAuthorization;


    public function viewAny(User $user)
    {
        //
    }


    public function view(User $user, Attachment $attachment)
    {
        //
    }


    public function create(User $user)
    {
        return $user->role_id==Role::team_member||$user->role_id==Role::team_leader;
    }


    public function update(User $user)
    {
        return $user->role_id==Role::team_member || $user->role_id==Role::team_leader;
    }


    public function delete(User $user)
    {
        return $user->role_id==Role::team_member || $user->role_id==Role::team_leader;
    }


    public function restore(User $user, Attachment $attachment)
    {
        //
    }


    public function forceDelete(User $user, Attachment $attachment)
    {
        //
    }
}
