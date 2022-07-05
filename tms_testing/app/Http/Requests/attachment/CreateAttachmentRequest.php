<?php

namespace App\Http\Requests\attachment;

use App\Models\Attachment;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CreateAttachmentRequest extends FormRequest
{

    public function authorize()
    {
        if(Auth::check())
        {
            return Auth::user()->can('create',Attachment::class);
        }
    }


    public function rules()
    {
        return [
            'title'=>'required|min:10|max:500',
            'description'=>'required|url',
            'file'=>'mimes:csv,txt,xlx,xls,pdf',
            'FileName'=>'string',
            'FilePath'=>'string'
        ];
    }
}
