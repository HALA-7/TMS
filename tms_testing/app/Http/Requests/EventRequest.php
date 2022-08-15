<?php

namespace App\Http\Requests;

use App\Models\Event;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class EventRequest extends FormRequest
{

    public function authorize()
    {
     return true;
    }


    public function rules()
    {
        return [
            'event_name'=>'string|min:5|max:70',
           // 'start_date'=>'date',
            //'end_date'=>'date',
            //'user_id'
        ];
    }
}
