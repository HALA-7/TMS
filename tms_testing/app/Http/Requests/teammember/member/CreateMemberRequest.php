<?php

namespace App\Http\Requests\teammember\member;

use App\Models\Member;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CreateMemberRequest extends FormRequest
{

    public function authorize()
    {
        if(Auth::check())
           return  Auth::user()->can('create',Member::class);
    }


    public function rules()
    {
        return [

            'img_profile'=>'image|mimes:jpg,png,jpeg,gif,svg|max:2048',//may be nullable
            'phone'=>'required|numeric|min:10',
            'contact'=>'max:200',
            'education'=>'min:5|max:100',
            //'user_id', no required we added from authentication
        ];
    }
}
