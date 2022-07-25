<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;


    public function viewAny(User $user)
    {
        return $user->role_id==Role::admin;
    }


    public function view(User $user, User $model)
    {
        return $user->role_id==Role::admin;
    }


    public function create(User $user)
    {
       return $user->role_id==Role::admin;
    }


    public function update(User $user)
    {
        return$user->role_id==Role::admin;
    }

    public function delete(User $user)
    {
        return$user->role_id==Role::admin;
    }


   /* public function restore(User $user, User $model)
    {

    }


    public function forceDelete(User $user, User $model)
    {

    }*/
}
