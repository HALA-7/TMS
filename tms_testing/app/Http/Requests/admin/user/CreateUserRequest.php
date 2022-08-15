<?php

namespace App\Http\Requests\admin\user;

use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CreateUserRequest extends FormRequest
{

    public function authorize()
    {
        if(Auth::check())
            return Auth::user()->can('create',User::class);

    }


    public function rules()
    {
        return [
            'first_name'=>'required|string|min:3|max:20',
            'last_name'=>'required|string|min:3|max:20',
            'email'=>'required|email|max:20',
            'employee_identical'=>'required|unique:App\Models\User,employee_identical|min:3|max:8',
            'password'=>'required|min:6|string',
            'role_id'=>'required',
             'team_id'=>'required',
        //    'remember_token'=>'boolean'
        ];

    }
}
