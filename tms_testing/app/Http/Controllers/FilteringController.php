<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Status;
use App\Models\Subtask;
use App\Models\Task;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FilteringController extends Controller
{
    public function  CompletedTask()
    {
        $this->authorize('TaskStatus',Task::class);
        if (Auth::check() && Auth::user()->role_id == Role::admin)
        {
            $result= DB::table('tasks')
                ->where('status_id','=',Status::Completed)->get();
            return response()->json(['the completed tasks'=>$result]);

        }
        //LEADER
        else if (Auth::user()->role_id == Role::team_leader)
        {
            $result= DB::table('tasks')
                ->where('team_id', '=', Auth::user()->team_id)
                ->where('status_id','=',Status::Completed)
                ->get();
            return response()->json(['the completed tasks'=>$result]);

        }


    }
    //-------------------------------------------------

    public function  OnProgressTask()
    {
        $this->authorize('TaskStatus',Task::class);

        if (Auth::check() && Auth::user()->role_id == Role::admin)
        {
            $result= DB::table('tasks')
                ->where('status_id','=',Status::On_Progress)->get();
            return response()->json(['in progress tasks'=>$result]);

        }
        //LEADER
        else if (Auth::user()->role_id == Role::team_leader)
        {
            $result= DB::table('tasks')
                ->where('team_id', '=', Auth::user()->team_id)
                ->where('status_id','=',Status::On_Progress)
                ->get();
            return response()->json(['in progress tasks'=>$result]);

        }
    }

    public function  MissedTask()
    {
        $this->authorize('TaskStatus',Task::class);
        if (Auth::check() && Auth::user()->role_id == Role::admin)
        {
            $result= DB::table('tasks')
                ->where('status_id','=',Status::Missed)->get();
            return response()->json(['the missed tasks'=>$result]);

        }
        //LEADER
        else if (Auth::user()->role_id == Role::team_leader)
        {
            $result= DB::table('tasks')
                ->where('team_id', '=', Auth::user()->team_id)
                ->where('status_id','=',Status::Missed)
                ->get();
            return response()->json(['the missed tasks'=>$result]);

        }
    }

    public function  To_DoTask()
    {
        $this->authorize('TaskStatus',Task::class);
        if (Auth::check() && Auth::user()->role_id == Role::admin)
        {
            $result= DB::table('tasks')
                ->where('status_id','=',Status::To_DO)->get();
            return response()->json(['to to tasks'=>$result]);

        }
        //LEADER
        else if (Auth::user()->role_id == Role::team_leader)
        {
            $result= DB::table('tasks')
                ->where('team_id', '=', Auth::user()->team_id)
                ->where('status_id','=',Status::To_DO)
                ->get();
            return response()->json(['to do tasks'=>$result]);

        }
    }

    //-----------------------------------SUB TASK---------------------------------

    public function  CompletedSubTask()
    {
        if(Auth::user()->role_id==Role::team_leader)
        {
            $result= DB::table('subtasks')
                ->join('tasks','subtasks.task_id','=','tasks.id')
                ->select('subtasks.*')
                ->where('tasks.team_id','=',Auth::user()->team_id)
                ->where('subtasks.status_id','=',Status::Completed)
                ->orderBy('subtasks.end_at','asc')
                ->get();
            return response()->json(['the completed sub tasks'=>$result]);
        }
        // MEMBER
        else if(Auth::user()->role_id==Role::team_member)
        {
            $result= DB::table('users')
                ->join('members', 'users.id', '=', 'members.user_id')
                ->join('member_subtask', 'members.id', '=', 'member_subtask.member_id')
                ->join('subtasks', 'member_subtask.subtask_id', '=', 'subtasks.id')
                ->select('subtasks.*')
                ->where('users.id', '=', Auth::id())
                ->where('subtasks.status_id','=',Status::Completed)
                ->orderBy('subtasks.end_at','asc')
                ->get();
            return response()->json(['the completed sub tasks'=>$result]);
        }


    }

    public function  MissedSubTask()
    {

        $this->authorize('SubStatus',Subtask::class);
        if(Auth::user()->role_id==Role::team_leader)
        {
            $result= DB::table('subtasks')
                ->join('tasks','subtasks.task_id','=','tasks.id')
                ->select('subtasks.*')
                ->where('tasks.team_id','=',Auth::user()->team_id)
                ->where('subtasks.status_id','=',Status::Missed)
                ->orderBy('subtasks.end_at','asc')
                ->get();
            return response()->json(['the missed sub tasks'=>$result]);
        }
        // MEMBER
        else if(Auth::user()->role_id==Role::team_member)
        {
            $result= DB::table('users')
                ->join('members', 'users.id', '=', 'members.user_id')
                ->join('member_subtask', 'members.id', '=', 'member_subtask.member_id')
                ->join('subtasks', 'member_subtask.subtask_id', '=', 'subtasks.id')
                ->select('subtasks.*')
                ->where('users.id', '=', Auth::id())
                ->where('subtasks.status_id','=',Status::Missed)
                ->orderBy('subtasks.end_at','asc')
                ->get();
            return response()->json(['the missed sub tasks'=>$result]);
        }

    }

    public function  ProgressSubTask()
    {
        $this->authorize('SubStatus',Subtask::class);
        if(Auth::user()->role_id==Role::team_leader)
        {
            $result= DB::table('subtasks')
                ->join('tasks','subtasks.task_id','=','tasks.id')
                ->select('subtasks.*')
                ->where('tasks.team_id','=',Auth::user()->team_id)
                ->where('subtasks.status_id','=',Status::On_Progress)
                ->orderBy('subtasks.end_at','asc')
                ->get();
            return response()->json(['progress sub tasks'=>$result]);
        }
        // MEMBER
        else if(Auth::user()->role_id==Role::team_member)
        {
            $result= DB::table('users')
                ->join('members', 'users.id', '=', 'members.user_id')
                ->join('member_subtask', 'members.id', '=', 'member_subtask.member_id')
                ->join('subtasks', 'member_subtask.subtask_id', '=', 'subtasks.id')
                ->select('subtasks.*')
                ->where('users.id', '=', Auth::id())
                ->where('subtasks.status_id','=',Status::On_Progress)
                ->orderBy('subtasks.end_at','asc')
                ->get();
            return response()->json(['progress sub tasks'=>$result]);
        }
    }

    public function  ToDoSubTask()
    {
        $this->authorize('SubStatus',Subtask::class);
        if(Auth::user()->role_id==Role::team_leader)
        {
            $result= DB::table('subtasks')
                ->join('tasks','subtasks.task_id','=','tasks.id')
                ->select('subtasks.*')
                ->where('tasks.team_id','=',Auth::user()->team_id)
                ->where('subtasks.status_id','=',Status::To_DO)
                ->orderBy('subtasks.end_at','asc')
                ->get();
            return response()->json(['to do sub tasks'=>$result]);
        }
        // MEMBER
        else if(Auth::user()->role_id==Role::team_member)
        {
            $result= DB::table('users')
                ->join('members', 'users.id', '=', 'members.user_id')
                ->join('member_subtask', 'members.id', '=', 'member_subtask.member_id')
                ->join('subtasks', 'member_subtask.subtask_id', '=', 'subtasks.id')
                ->select('subtasks.*')
                ->where('users.id', '=', Auth::id())
                ->where('subtasks.status_id','=',Status::To_DO)
                ->orderBy('subtasks.end_at','asc')
                ->get();
            return response()->json(['to do sub tasks'=>$result]);
        }
    }

    public function LateSubTask()
    {
        $this->authorize('SubStatus',Subtask::class);
        if(Auth::user()->role_id==Role::team_leader)
        {
            $result= DB::table('subtasks')
                ->join('tasks','subtasks.task_id','=','tasks.id')
                ->select('subtasks.*')
                ->where('tasks.team_id','=',Auth::user()->team_id)
                ->where('subtasks.status_id','=',Status::Late)
                ->orderBy('subtasks.end_at','asc')
                ->get();
            return response()->json(['the late sub tasks'=>$result]);
        }
        // MEMBER
        else if(Auth::user()->role_id==Role::team_member)
        {
            $result= DB::table('users')
                ->join('members', 'users.id', '=', 'members.user_id')
                ->join('member_subtask', 'members.id', '=', 'member_subtask.member_id')
                ->join('subtasks', 'member_subtask.subtask_id', '=', 'subtasks.id')
                ->select('subtasks.*')
                ->where('users.id', '=', Auth::id())
                ->where('subtasks.status_id','=',Status::Late)
                ->orderBy('subtasks.end_at','asc')
                ->get();
            return response()->json(['the late sub tasks'=>$result]);
        }
    }

}
