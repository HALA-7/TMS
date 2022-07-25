<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\admin\meeting\CreateMeetingRequest;
use App\Http\Requests\admin\meeting\UpdateMeetingRequest;
use App\Http\Requests\admin\task\UpdateTaskRequest;
use App\Models\Team;
use App\Models\Meeting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class MeetingController extends Controller
{
    public $temp_array;

   /* public function index()
    {

       if(Auth::check()) {

           $this->authorize('viewAny', Meeting::class);
           $time = Meeting::query()->get();

           foreach ($time as $t) {
               if ($t->meeting_date < Carbon::now()) {
                   $t->update([
                       'meeting_statuses_id' => 2,//finised
                   ]);
               }
           }

           $all_meeting = Meeting::with(['users'=>function($q){
               $q->with(['leaders','members']);
           }])->get();
           return \response()->json( $all_meeting);


       }

    }*/


    public function store(CreateMeetingRequest $request)
    {
        $meeting=Meeting::query()->create([
            //'title'=>$request->title,
            'meeting_date'=>$request->meeting_date,
            'start_at'=>$request->start_at,
            'meeting_statuses_id'=>Meeting::UpComing
        ]);

        foreach ($request->participant_list as $i)
        {
            $meeting->users()->attach($i);

        }

        return response()->json(['message'=>'Added successfully',$meeting,
            'with'=>$meeting->users()->get()],201);
    }




    public function update(UpdateMeetingRequest $request, Meeting $meet)
    {
        $meet->update([
        //    'title'=>$request->title,
          //  'discussion_point'=>$request->discussion_point,
            'meeting_date'=>$request->meeting_date,
            'start_at'=>$request->start_at,
            'meeting_statuses_id'=>$request->meeting_statuses_id
        ]);

      foreach ($request->participant_list as $i) {
            $temp_array[]=$i;
        }

        $meet->users()->sync($temp_array);

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
