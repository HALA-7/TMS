<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use App\Models\MeetingStatus;
use App\Models\Priority;
use App\Models\Role;
use App\Models\Status;
use App\Models\Subtask;
use App\Models\Task;
use App\Models\Team;
use App\Models\User;
use App\Notifications\SubTaskRemenderNotification;
use Carbon\Carbon;
use http\Client\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class ShowController extends Controller
{



//--------------------TO SHOW ALL THE TEAMS-------------------
    public function ShowMyTeam()
    {
        if(Auth::check())
        {  $this->authorize('MyTeam',Team::class);


            $my_members = DB::table('users')
                ->join('members', 'users.id', '=', 'members.user_id')
                ->select('first_name', 'last_name', 'email', 'members.*')
                ->where('team_id', '=', Auth::user()->team_id)
                ->get();

            if(Auth::user()->role_id==Role::team_member)
            {
                $my_leader = DB::table('users')
                    ->join('leaders', 'users.id', '=', 'leaders.user_id')
                    ->select('first_name', 'last_name', 'email', 'leaders.*')
                    ->where('team_id', '=', Auth::user()->team_id)
                    ->get();

                return \response()->json(['the leader of team' => $my_leader, 'my team' => $my_members]);
            }
            return \response()->json(['my team' => $my_members]);
        }

    }
//------------------TO SHOW ALL MEETINGS THAT I WAN IN IT-------------------
    public function ShowMyMeetings()
    {
        $info_about_meetings[]='';

        if(Auth::check())
        {
            //ADMIN
            if(Auth::user()->role_id==Role::admin)
            {
                $time = Meeting::query()->get();

                foreach ($time as $t) {
                    $temp = Meeting::find($t->id);
                    if ($t->meeting_date < Carbon::now() && $temp->meeting_statuses_id != MeetingStatus::Finished)
                    {
                        $temp = Meeting::find($t->id);
                        $temp->update(['meeting_statuses_id' => 2]);
                    }
                    $all_meeting = Meeting::with(['users' => function ($q) {
                        $q->with(['leaders', 'members']);
                    }])->get();

                    /*$test1= DB::table('meetings')
                        ->join('meeting_user', 'meetings.id', '=', 'meeting_user.meeting_id')
                        ->join('users', 'meeting_user.user_id', '=', 'users.id')
                        ->join('leaders','users.id','leaders.user_id')
                        ->select( 'users.first_name', 'users.last_name','leaders.img_profile')
                        ->where('meetings.id','=',$t->id)
                        ->get();

                    $test2= DB::table('meetings')
                        ->join('meeting_user', 'meetings.id', '=', 'meeting_user.meeting_id')
                        ->join('users', 'meeting_user.user_id', '=', 'users.id')
                        ->join('members','users.id','members.user_id')
                        ->select( 'users.first_name', 'users.last_name','members.img_profile')
                        ->where('meetings.id','=',$t->id)
                        ->get();

                    $info_about_meetings[] = ['meeting' => $t, 'with' => [$test1,$test2]];
                    */
                }
                //return \response()->json($info_about_meetings);
                return \response()->json($all_meeting);
            }

            //LEADER  && MEMBERS
            $this->authorize('MyMeeting',Meeting::class);

            $meetings = DB::table('meetings')
                ->join('meeting_user', 'meetings.id', '=', 'meeting_user.meeting_id')
                ->select('meetings.*')
                ->where('meeting_user.user_id', '=', Auth::id())
                ->get();


            foreach ($meetings as $meet)
            {

                if ($meet->meeting_date < Carbon::now() && $meet->meeting_statuses_id!=MeetingStatus::Finished) {
                    $temp=Meeting::find($meet->id);
                    $temp->update(['meeting_statuses_id' =>2]);
                    // dd('the condition is true');
                }

                /*$meeting = DB::table('users')
                    ->join('meeting_user', 'users.id', '=', 'meeting_user.user_id')
                    ->select('users.first_name', 'users.last_name')
                    ->where('meeting_user.meeting_id', '=', $meet->id)
                    ->get();
                $info_about_meetings[] = ['meeting' => $meet, 'with' => $meeting];*/

                $info_about_meetings[] = Meeting::with(['users' => function ($q) {
                    $q->with(['leaders', 'members']);
                }])->where('meetings.id','=',$meet->id)->get();

            }//END FOR

            return \response()->json($info_about_meetings);


        }

    }
// ----------------------------------------SHOW SPECIFIC MEETING WITH ALL INFO ABOUT IT----------------------------
    public function  ShowOneMeeting(Meeting $id)
    {
        $info_about_meetings[] = '';


        if (Auth::check()) {

            $temp = Meeting::find($id->id);

            //ADMIN
            if (Auth::user()->role_id == Role::admin) {

                if ($temp->meeting_date < Carbon::now() && $temp->meeting_statuses_id != MeetingStatus::Finished) {
                    $temp->update(['meeting_statuses_id' => 2]);
                }


                $all_meeting = Meeting::with(['users' => function ($q) {
                    $q->with(['leaders', 'members']);
                }])->where('meetings.id', '=', $id->id)->get();

                return \response()->json($all_meeting);
            }

            //LEADER  && MEMBERS
            else{
                $this->authorize('MyMeeting', Meeting::class);

                //give all meetings with its user-id
                $var = DB::table('meetings')
                    ->join('meeting_user', 'meetings.id', 'meeting_user.meeting_id')
                    ->select('meeting_user.user_id')
                    ->where('meeting_user.meeting_id', '=', $id->id)
                    ->get();
                $array = [];
                foreach ($var as $v) {
                    $array[] = $v->user_id;
                }

                if (in_array(Auth::user()->id, $array, true))
                {
                    if ($temp->meeting_date < Carbon::now() && $temp->meeting_statuses_id != MeetingStatus::Finished) {
                        $temp->update(['meeting_statuses_id' => 2]);
                    }

                    $all_meeting = Meeting::with(['users' => function ($q) {
                        $q->with(['leaders', 'members']);
                    }])->where('meetings.id', '=', $id->id)->get();

                    return \response()->json($all_meeting);

                }
                else
                {
                    return \response()->json(['message' => 'you are not in this meeting']);
                }

            }

        }
    }

//----------------TO SHOW MY PROFILE THAT I CREATED ----------------------
    public function ShowMyProfile()
    {
        if(Auth::check() && Auth::user()->role_id==Role::team_leader) {
            $profile = DB::table('users')
                ->join('leaders', 'leaders.user_id', '=', 'users.id')
                ->select('leaders.*', 'users.*')
                ->where('users.id', '=', Auth::id())
                ->get();
            return response()->json(['My Profile:' => $profile]);
        }
        else if(Auth::check() && Auth::user()->role_id==Role::team_member)
        {
            $profile = DB::table('users')
                ->join('members', 'members.user_id', '=', 'users.id')
                ->select('members.*', 'users.*')
                ->where('users.id', '=', Auth::id())
                ->get();

            return response()->json(['My Profile:' => $profile]);
        }
        else
            return response()->json(['un authorization'],403);
    }

    //----------------THIS FUNCTION TO RECALL THE SAME QUERY IN DIFFERENT METHOD  IN THIS CONTROLLER----------------
    public  function TaskHelper()
    {
        //Admin
        if (Auth::check() && Auth::user()->role_id == Role::admin) {
            return Task::query()->get();

        }
        //LEADER
        else if (Auth::user()->role_id == Role::team_leader)
        {
            return DB::table('tasks')
                ->where('team_id', '=', Auth::user()->team_id)
               // ->orderBy('end_date')
                ->get();

        }
        // MEMBER
        else if(Auth::user()->role_id == Role::team_member)
        {
            return DB::table('users')
                ->join('members','users.id','=','members.user_id')
                ->join('member_subtask','members.id','=','member_subtask.member_id')
                ->join('subtasks', 'member_subtask.subtask_id', '=', 'subtasks.id')
                ->join('tasks','subtasks.task_id','=','tasks.id')
                ->select('tasks.*')->distinct()
                ->where('users.id','=',Auth::id())
                ->get();
            // because one member can be in 2 or more subtask for one task
        }


    }
//-------------------THIS FUNCTION TO RECALL THE SAME QUERY IN DIFFERENT METHOD  IN THIS CONTROLLER----------------
    public function SubTaskHelper()
    {
        if(Auth::check())
        {
            // LEADER
            if(Auth::user()->role_id==Role::team_leader)
            {
                return DB::table('subtasks')
                    ->join('tasks','subtasks.task_id','=','tasks.id')
                    ->select('subtasks.*')
                    ->where('tasks.team_id','=',Auth::user()->team_id)
                    ->orderBy('subtasks.end_at','asc')
                    ->get();
            }
            // MEMBER
            else if(Auth::user()->role_id==Role::team_member)
            {
                return DB::table('users')
                    ->join('members', 'users.id', '=', 'members.user_id')
                    ->join('member_subtask', 'members.id', '=', 'member_subtask.member_id')
                    ->join('subtasks', 'member_subtask.subtask_id', '=', 'subtasks.id')
                    ->select('subtasks.*')
                    ->where('users.id', '=', Auth::id())
                    ->orderBy('subtasks.end_at','asc')
                    ->get();
            }//end for

        }
    }


    //---------------TO SHOW THE ALL TASKS--------------------
    public  function ShowMyTask()
    {
        //Admin
        if (Auth::check() && Auth::user()->role_id == Role::admin) {
            //  $this->authorize('viewAny', Task::class);
            //$p=$this->TaskHelper();
            return \response()->json($this->TaskHelper());

        }

        $this->authorize('MyTask',Task::class);

        //LEADER
        if (Auth::user()->role_id == Role::team_leader)
        {
            $Tasks = $this->TaskHelper();

        }
        // MEMBER
        else{
            $Tasks=$this->TaskHelper();
        }
        return \response()->json(['my task' =>  $Tasks]);

    }

    //---------------------------TO SHOW SPECIFIC INFO ABOUT ONE TASK--------------------------
    public function Details(Task $task)
    {
        if(Auth::check())
        {
            $sub=Task::with(['subtasks'=> function($q){
                $q->with(['members'=>function($q2) {
                    $q2->join('users','members.user_id','=','users.id')
                        ->select('users.first_name','users.last_name','members.*')->get();
                }])->get();
            }])->where('tasks.id','=',$task->id)
                ->get();

            return \response()->json(['the info about task'=>$sub]);


        }


        /*
       if($task->team_id==Auth::user()->team_id)
        {
            $subtasks=Task::with(['subtasks'=> function($q){
                $q->with(['members'=>function($q2) {
                    $q2->join('users','members.user_id','=','users.id')
                        ->select('users.first_name','users.last_name','members.*')->get();
                }])->get();
            }])
                ->where('tasks.team_id','=',Auth::user()->team_id)
                ->where('tasks.id','=',$task->id)
                ->get();
            return \response()->json(['details '=>$subtasks]);
        }
        else
        {
            return \response()->json(['Not Allowed']);
        }
          */

    }

//-----------------TO SHOW SUBTASKS-----------------------
    public function ShowMySubTask()
    {
        if(Auth::check())
        {
            $this->authorize('MySubTask',Subtask::class);

            // LEADER
            if(Auth::user()->role_id==Role::team_leader)
            {
                $all_subtask[]='';
                $my_subtask=DB::table('subtasks')
                    ->join('tasks','subtasks.task_id','=','tasks.id')
                    ->select('subtasks.*')
                    ->where('tasks.team_id','=',Auth::user()->team_id)
                    ->get();

                // return  \response()->json( $my_subtask);

                foreach($my_subtask as $temp)
                {
                    $assigned_to=DB::table('subtasks')
                        ->join('member_subtask','subtasks.id','member_subtask.subtask_id')
                        ->join('members','member_subtask.member_id','members.id')
                        ->join('users','members.user_id','users.id')
                        ->select('users.first_name','users.last_name','members.img_profile')
                        ->where('subtasks.id','=',$temp->id)
                        ->get();

                    $all_subtask[]=['the subtask is'=>$temp,'assigned to'=>$assigned_to];

                }

                return  \response()->json($all_subtask);


            }

            // MEMBER
            else
            {
                $all_subtask[]='';

                // to get all subtask that assigned to me
                $task_for_me = DB::table('users')
                    ->join('members', 'users.id', '=', 'members.user_id')
                    ->join('member_subtask', 'members.id', '=', 'member_subtask.member_id')
                    ->join('subtasks', 'member_subtask.subtask_id', '=', 'subtasks.id')
                    ->select('subtasks.*')
                    ->where('users.id', '=', Auth::id())
                    ->get();

                foreach ($task_for_me as $tt) {
                    $with_me = DB::table('users')
                        ->join('members', 'users.id', '=', 'members.user_id')
                        ->join('member_subtask', 'members.id', '=', 'member_subtask.member_id')
                        ->select('users.first_name', 'users.last_name', 'members.img_profile')
                        ->where('member_subtask.subtask_id', '=', $tt->id)
                        ->get();

                    $all_subtask[]=[$tt,$with_me];


                }
                //end for
                return response()->json($all_subtask);
            }//end member else
        }
    }

    //------------------TO SHOW SPECIFIC SUBTASK WITH ALL INFO----------------------
    public function ShowOneSubTask(Subtask $id)
    {
        $ans=Subtask::with(['members'=>function($q2) {
            $q2->join('users','members.user_id','=','users.id')
                ->select('users.first_name','users.last_name','members.*')->get();
        }])->where('subtasks.id','=',$id->id)->get();

        return \response()->json(['the info about subtask'=>$ans]);


    }

    //----------------SEARCH ABOUT TASK YOU WANT-------------
    public function TaskSearch(Request $request)
    {
        $ans='';
        $task='';
        if (Auth::check() && Auth::user()->role_id == Role::admin) {
            $task= Task::query();

        }
        //LEADER
        else if (Auth::user()->role_id == Role::team_leader)
        {
            $task=DB::table('tasks')
                ->where('team_id', '=', Auth::user()->team_id);

        }
        // MEMBER
        else if(Auth::user()->role_id == Role::team_member)
        {
            $task= DB::table('users')
                ->join('members','users.id','=','members.user_id')
                ->join('member_subtask','members.id','=','member_subtask.member_id')
                ->join('subtasks', 'member_subtask.subtask_id', '=', 'subtasks.id')
                ->join('tasks','subtasks.task_id','=','tasks.id')
                ->select('tasks.*')
                ->where('users.id','=',Auth::id());
            // ->where('subtasks.id','','tasks.id')


        }
        $key_search=$request->title;
        // dd($task->where('tasks.title', 'like', '%' . $key_search . '%')->get());


        if($key_search)
            $ans=$task->where('tasks.title', 'like', '%' . $key_search . '%');
        if($request->start_date){
            $ans=$task->where('tasks.start_date','=',$request->start_date);}

        if($request->end_date){
            $ans=$task->where('tasks.end_date','=',$request->end_date);
        }
        if($request->status_id){
            $ans= $task->where('tasks.status_id','=',$request->status_id);
        }

        return \response()->json($ans->get());

    }

    //-------------------TO SEARCH ABOUT SUBTASK-----------------------------
    public  function SubTaskSearch(Request $request)
    {

        $ans='';
        $subtask='';
        //LEADER
        if(Auth::user()->role_id==Role::team_leader)
        {

            $subtask=DB::table('subtasks')
                ->join('tasks','subtasks.task_id','=','tasks.id')
                ->select('subtasks.*')
                ->where('tasks.team_id','=',Auth::user()->team_id);

        }

        // MEMBER
        else
        {
            // to get all subtask that assigned to me
            $subtask= DB::table('users')
                ->join('members', 'users.id', '=', 'members.user_id')
                ->join('member_subtask', 'members.id', '=', 'member_subtask.member_id')
                ->join('subtasks', 'member_subtask.subtask_id', '=', 'subtasks.id')
                ->select('subtasks.*')
                ->where('users.id', '=', Auth::id());

        }

        $key_search=$request->title;



        if($key_search)
            $ans=$subtask->where('subtasks.title', 'like', '%' . $key_search . '%');

        if($request->start_at){
            $ans=$subtask->where('subtasks.start_at','=',$request->start_at);
        }

        if($request->end_at){
            $ans=$subtask->where('subtasks.end_at','=',$request->end_at);
        }

        if($request->priority_id){
            $ans=$subtask->where('subtasks.priority_id','=',$request->priority_id);
        }

        if($request->status_id){
            $ans=$subtask->where('subtasks.status_id','=',$request->status_id);

        }
        return \response()->json($ans->get());
    }


















}
