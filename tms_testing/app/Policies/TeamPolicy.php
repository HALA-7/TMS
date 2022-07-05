<?php

namespace App\Policies;

use App\Models\Team;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TeamPolicy
{
    use HandlesAuthorization;


    public function viewAny(User $user)
    {
        return $user->role_id==Role::admin;
    }


    public function view(User $user, Team $id)
    {
        return $user->role_id==Role::admin;
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


    /*public function restore(User $user, Team $department)
    {
        //
    }


    public function forceDelete(User $user, Team $department)
    {
        //
    }
    */
}
