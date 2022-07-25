<?php

namespace App\Policies;

use App\Models\Member;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class MemberPolicy
{
    use HandlesAuthorization;


    public function viewAny(User $user)
    {
        $member= Member::where('user_id',Auth::id())->value('user_id');
        return  ($user->role_id==Role::team_member)&&(Auth::id()==$member);
    }


    public function view(User $user)
    {

        return  $user->role_id==Role::team_member;
    }


    public function create(User $user)
    {
        return $user->role_id==Role::team_member;
    }


    public function update(User $user,Member $member)
    {

        return  ($user->role_id==Role::team_member)&&(Auth::id()==$member->user_id);
    }


   /* public function delete(User $user, Member $member)
    {
        //
    }


    public function restore(User $user, Member $member)
    {
        //
    }


    public function forceDelete(User $user, Member $member)
    {
        //
    }
   */
}
