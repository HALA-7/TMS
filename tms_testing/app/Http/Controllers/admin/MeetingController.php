<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\admin\meeting\CreateMeetingRequest;
use App\Http\Requests\admin\meeting\UpdateMeetingRequest;
use App\Http\Requests\admin\task\UpdateTaskRequest;
use App\Models\Team;
use App\Models\Meeting;
use App\Models\User;
use App\Notifications\MeetingNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
//use Illuminate\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use phpDocumentor\Reflection\Types\Nullable;


class MeetingController extends Controller
{
    public $temp_array;


    public function store(CreateMeetingRequest $request)
    {   $test_array=[];
        $num_of_null=0;
        foreach ($request->participant_list as $i)
        {   $test_array[]=$i;
            if(is_null($i))
            {
                $num_of_null+=1;
            }

        }
        if($num_of_null==count($test_array))
        {
             return \response()->json(['message:'=>'can not create meeting with no participant'],422);
        }

        $meeting=Meeting::query()->create([
            'meeting_date'=>$request->meeting_date,
            'start_at'=>$request->start_at,
            'meeting_statuses_id'=>Meeting::UpComing
        ]);

        foreach ($request->participant_list as $i)
        {

            $meeting->users()->attach($i);

        }
       $temping=Meeting::query()->where('meetings.id','=',$meeting->id)->get();

        Notification::send($meeting->users()->get(),new MeetingNotification($temping));

       return response()->json(['message'=>'Added successfully',$meeting,
            'with'=>$meeting->users()->get()],201);
    }



    public function update(UpdateMeetingRequest $request, Meeting $meet)
    {
        $meet->update([

            'meeting_date'=>$request->meeting_date,
            'start_at'=>$request->start_at,
            'meeting_statuses_id'=>$request->meeting_statuses_id
        ]);

      foreach ($request->participant_list as $i) {
            $temp_array[]=$i;
        }
        $meet->users()->sync($temp_array);

        Notification::send($meet->users()->get(),new MeetingNotification($meet));

        return response()->json(['Updating successfully'=>$meet,
            'with'=>$meet->users()->get()],Response::HTTP_OK);
    }



    public function destroy(Meeting $meet)
    {   if(Auth::check())
      {
        $this->authorize('delete',Meeting::class);
        $meet->delete();
        return response()->json(['message'=>'deleted is done'],Response::HTTP_OK);
      }
    }


}
