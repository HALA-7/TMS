<?php

namespace App\Http\Requests\teamleader\subtask;

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
            'title'=>'required|min:5|:max:50',
            'description'=>'required|min:10|max:150',
            'start_at'=>'required|date',
            'end_at'=>'required|date',
            'priority_id'=>'required',
            'status_id'=>'required',
            'user_list'=>'required' // the member that i will assigned to it
            // 'task_id'=>'required',
        ];
    }
}
