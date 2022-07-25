<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class EventPolicy
{
    use HandlesAuthorization;


    public function viewAny(User $user)
    {
        //
    }


    public function view(User $user, Event $id)
    {
        return $user->id==$id->user_id;
    }


    public function create(User $user)
    {
        return $user->role_id==Role::admin ||
               $user->role_id==Role::team_member ||
               $user->role_id==Role::team_leader;
    }


    public function update(User $user, Event $id)
    {
        return $user->id==$id->user_id;
    }


    public function delete(User $user, Event $id)
    {
        return $user->id==$id->user_id;
    }



    public function restore(User $user, Event $event)
    {
        //
    }


    public function forceDelete(User $user, Event $event)
    {
        //
    }
}
