<?php

namespace App\Http\Requests\teamleader\leader;

use App\Models\Leader;
use App\Models\Role;
use App\Models\User;
use App\Policies\LeaderPolicy;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;


class CreateLeaderRequest extends FormRequest
{
    public function authorize()
    {
        if(Auth::check())
        return Auth::user()->can('create',Leader::class);

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
