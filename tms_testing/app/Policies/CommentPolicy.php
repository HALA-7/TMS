<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class CommentPolicy
{
    use HandlesAuthorization;


    public function viewAny(User $user)
    {
        //
    }


    public function view(User $user, Comment $comment)
    {
        //
    }


    public function create(User $user)
    {
        return $user->role_id==Role::team_leader || $user->role_id==Role::team_member;
    }


    public function update(User $user)
    {
       return $user->role_id==Role::team_member || $user->role_id==Role::team_leader;
    }


    public function delete(User $user)
    {
        return $user->role_id==Role::team_member || $user->role_id==Role::team_leader;
    }


    public function restore(User $user, Comment $comment)
    {
        //
    }


    public function forceDelete(User $user, Comment $comment)
    {
        //
    }
}
