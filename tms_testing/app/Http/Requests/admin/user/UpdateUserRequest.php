<?php

namespace App\Http\Requests\admin\user;

use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if(Auth::check())
            return Auth::user()->can('update',User::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'first_name'=>'required|string|min:3|max:20',
            'last_name'=>'required|string|min:3|max:20',
            'email'=>'required|email|max:20',
            'employee_identical'=>'required|min:3|max:8',
            'password'=>'required|min:6|string',
            'role_id'=>'required',
            'team_id'=>'required',
           // 'remember_token'=>'boolean'
        ];
    }
}
