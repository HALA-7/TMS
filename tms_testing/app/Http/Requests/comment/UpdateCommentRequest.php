<?php

namespace App\Http\Requests\comment;

use App\Models\Comment;
use App\Models\Role;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateCommentRequest extends FormRequest
{

    public function authorize()
    {
       if(Auth::check())
       {
         return Auth::user()->can('update',Comment::class);
       }
    }

    public function rules()
    {
        return [
            'body'=>'required|min:10|max:200'
        ];
    }
}
