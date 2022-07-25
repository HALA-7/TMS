<?php

namespace App\Http\Requests\admin\meeting;

use App\Models\Team;
use App\Models\Meeting;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CreateMeetingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if(Auth::check())
            return Auth::user()->can('create',Meeting::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
           //  'title'=>'required',
           // 'discussion_point'=>'required|max:250',
            'meeting_date'=>'required|date|after_or_equal:today',
            'start_at'=>'required|date_format:"H:i"',
            'participant_list'=>'required', // to define the user that i want to meeting them
           // 'meeting_statuses_id'=>'required'
        ];
    }
}
