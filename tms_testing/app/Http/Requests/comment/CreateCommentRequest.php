<?php

namespace App\Http\Requests\comment;

use App\Models\Comment;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CreateCommentRequest extends FormRequest
{

    public function authorize()
    {
        if(Auth::check())
        {
                return Auth::user()->can('create',Comment::class);
        }
    }

    public function rules()
    {
        return [
            'body'=>'required|string|min:10|max:200'
        ];
    }
}
