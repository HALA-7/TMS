<?php

namespace App\Policies;

use App\Models\Meeting;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MeetingPolicy
{
    use HandlesAuthorization;


   // public function viewAny(User $user)
    /*{
        //
    }*/


    public function view(User $user, Meeting $meeting)
    {
        //
    }


    public function create(User $user)
    {
        return $user->role_id==Role::admin;
    }


    public function update(User $user)
    {
        return $user->role_id==Role::admin;
    }


    public function delete(User $user)
    {
        return $user->role_id==Role::admin;
    }


   /* public function restore(User $user, Meeting $meeting)
    {
        //
    }

    public function forceDelete(User $user, Meeting $meeting)
    {
        //
    }
   */
}
