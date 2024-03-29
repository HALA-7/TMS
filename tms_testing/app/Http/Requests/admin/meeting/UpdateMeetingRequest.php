<?php

namespace App\Http\Requests\admin\meeting;

use App\Models\Team;
use App\Models\Meeting;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateMeetingRequest extends FormRequest
{

    public function authorize()
    {
        if(Auth::check()) {
            return Auth::user()->can('update',Meeting::class);
        }
    }


    public function rules()
    {
        return [

            'meeting_date'=>'required|date|after_or_equal:today',
            'start_at'=>'required|date_format:"H:i"',
            'participant_list'=>'required', // to define the user that i want to meeting them
            'meeting_statuses_id'=>'required'
        ];
    }
}
