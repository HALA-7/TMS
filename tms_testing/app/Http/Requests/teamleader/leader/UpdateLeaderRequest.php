<?php

namespace App\Http\Requests\teamleader\leader;

use App\Models\Leader;
use App\Models\Role;
use App\Models\User;
use App\Policies\LeaderPolicy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\Console\Helper\Table;

class UpdateLeaderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if(Auth::check())
        {
            return Auth::user()->can('update',Leader::class) ;
        }

     //   if(Auth::check())
       // { $user=Auth::user();
         //   $leader= Leader::where('user_id',Auth::id())->value('user_id');

           // return  ($user->role_id==Role::team_leader)&&
             //   (Auth::id()==$leader);

        //}



    }

    public function rules()
    {
        return [
            'img_profile'=>'image|mimes:jpg,png,jpeg,gif|max:2048',//may be nullable
            'phone'=>'required|numeric|min:10',
            'contact'=>'max:200',
            'education'=>'min:5|max:100',
            'experience'=>'min:5|max:100',
            // 'user_id'=>'', not required we will get it from authenticated user
        ];
    }
}
