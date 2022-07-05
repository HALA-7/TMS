<?php

namespace App\Policies;



use App\Models\Leader;
use App\Models\Meeting;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;


class LeaderPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        //
    }


    public function view(User $user)
    {
        //
    }


    public function create(User $user)
    {
        return $user->role_id==Role::team_leader;

    }


    public function update(User $user)
    {
          // the $user in parameter of update function is to check role id
          // the $leader is to check if this is a user who create the info
          $leader= Leader::where('user_id',Auth::id())->value('user_id');
         return  ($user->role_id==Role::team_leader)&& (Auth::id()==$leader);


    }


   public function delete(User $user, LeaderPolicy $leaderPolicy)
    {
        //
    }


    public function restore(User $user, LeaderPolicy $leaderPolicy)
    {
        //
    }


    public function forceDelete(User $user, LeaderPolicy $leaderPolicy)
    {
        //
    }
}
