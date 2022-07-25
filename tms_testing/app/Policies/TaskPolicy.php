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
        return$user->role_id==Role::admin;
    }

    public function view(User $user, Task $id)
    {
        return$user->role_id==Role::admin;
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


    public function MyTask(User $user)
    {
        return$user->role_id==Role::team_member||$user->role_id==Role::team_leader;
    }

    public function TaskStatus(User $user)
    {
        return$user->role_id==Role::admin||$user->role_id==Role::team_leader;
    }




    /*public function forceDelete(User $user, Task $task)
    {
        //
    }
    */
}
