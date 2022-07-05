<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\Task;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TaskPolicy
{
    use HandlesAuthorization;


    public function viewAny(User $user)
    {
        //
    }

    public function view(User $user, Task $task)
    {
        //
    }


    public function create(User $user)
    {
        return$user->role_id==Role::admin;
    }


    public function update(User $user)
    {
        return$user->role_id==Role::admin;
    }


    public function delete(User $user)
    {
        return$user->role_id==Role::admin;
    }


    /*public function restore(User $user, Task $task)
    {
        //
    }


    public function forceDelete(User $user, Task $task)
    {
        //
    }
    */
}
