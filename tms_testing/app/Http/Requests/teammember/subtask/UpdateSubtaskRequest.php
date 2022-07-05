<?php

namespace App\Http\Requests\teammember\subtask;

use App\Models\Subtask;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateSubtaskRequest extends FormRequest
{

    public function authorize()
    {
        if(Auth::check())
            return Auth::user()->can('update',Subtask::class);
    }

    public function rules()
    {
        return [
            'status_id'=>'required',
        ];
    }
}
