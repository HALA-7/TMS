<?php

namespace App\Http\Requests\teammember\member;

use App\Models\Member;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateMemberRequest extends FormRequest
{

    public function authorize()
    {
        if(Auth::check())
        {
            return true;
                //Auth::user()->can('update',Member::class) ;
        }
    }


    public function rules()
    {
        return [
            'img_profile'=>'image',//may be nullable
            'phone'=>'required|numeric|min:10',
            //'contact'=>'max:200',
            'contact'=>'url',
            'education'=>'min:5|max:100',
        ];
    }
}
