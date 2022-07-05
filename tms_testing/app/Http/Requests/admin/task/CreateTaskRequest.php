<?php

namespace App\Http\Requests\admin\task;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CreateTaskRequest extends FormRequest
{

    public function authorize()
    {
        if(Auth::check())
            return Auth::user()->can('create',Task::class);
    }


    public function rules()
    {
        return [
            'title'=>'required|min:3|max:50',
            'description'=>'required|min:10|max:150',
            'start_date'=>'required|date|after_or_equal:today',
            'end_date'=>'required|date|after:start_date',
            'status_id'=>'required',
            'team_id'=>'required'
        ];
    }
}
