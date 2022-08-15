<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Role;
use App\Models\Status;
use App\Models\Subtask;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use phpseclib3\Crypt\DSA\Formats\Keys\Raw;

class ReportController extends Controller
{
    public function  ShowReport()
    {
        //------------------------------REPORT-----------------------------


        // ARRAY OF ALL TASK AND ITS DETAILS AND PERCENTAGE
        $repo = [];

        $task = (new ShowController())->TaskHelper();
       // $all = Subtask::query()->count();

        foreach ($task as $tt)
        {   $completed=0;
            //TO GET ALL DETAILS ABOUT SPECIFIC TASK (ITS SUBTASK AND THE MEMBER)
            $ans = Task::where('tasks.id', '=', $tt->id)
                ->with(['subtasks' => function ($q2) {
                    $q2->with(['members' => function ($q3) {
                        $q3->join('users', 'members.user_id', 'users.id')
                            ->select('first_name', 'last_name', 'img_profile')
                            ->get();
                    }]);
                }])->get();

            //NUMBER OF COMPLETED SUBTASK FOR THIS TASK
            $number = DB::table('subtasks')
                ->where('task_id', '=', $tt->id)
                ->where('status_id', '=', Status::Completed)
                ->count();

            //NUMBER OF SUBTASK FOR THIS TASK
            $all = DB::table('subtasks')
                ->where('task_id', '=', $tt->id)
                ->count();
            //dd($number,$all);
            //the task has a subtask
            if($all>0) {
                //THE PERCENTAGE OF COMPLETED
                $completed = round((100 * $number) / $all);
            }
            $repo[] = ['the task' => $ans, 'the percentage' => $completed];


        }
        return response()->json($repo);
    }

    public function ShowStatistic()
    {
      if(Auth::user()->role_id==Role::admin || Auth::user()->role_id==Role::team_leader) {
          $task = (new ShowController())->TaskHelper();
          $all = $task->count();
          $num_of_completed = $task->where('status_id', '=', Status::Completed)->count();
          $num_of_missed = $task->where('status_id', '=', Status::Missed)->count();
          $num_of_todo = $task->where('status_id', '=', Status::To_DO)->count();
          $num_of_progress = $task->where('status_id', '=', Status::On_Progress)->count();


          return response()->json(['total tasks num ' => $all,
              'num of completed ' => $num_of_completed,
              'percentage completed ' => round((100 * $num_of_completed) / $all),
              'num of progress ' => $num_of_progress,
              'percentage progress ' => round((100 * $num_of_progress) / $all),
              'num of missed ' => $num_of_missed,
              'percentage missed ' => round((100 * $num_of_missed) / $all),
              'num of todo ' => $num_of_todo,
              'percentage todo ' => round((100 * $num_of_todo) / $all),

          ]);


      }

    }

    public function  ShowMemberStatistic()
    {
        if (Auth::user()->role_id == Role::team_member)
        {
            $num = (new ShowController())->SubTaskHelper();
            $all = $num->count();
            if($all>0) {
                $sub_completed = $num->where('status_id', '=', Status::Completed)->count();
                $sub_missed = $num->where('status_id', '=', Status::Missed)->count();
                $sub_todo = $num->where('status_id', '=', Status::To_DO)->count();
                $sub_progress = $num->where('status_id', '=', Status::On_Progress)->count();
                $sub_late = $num->where('status_id', '=', Status::Late)->count();

                return response()->json(['total subtasks num ' => $all,
                    'num of completed ' => $sub_completed,
                    'percentage completed ' => round((100 * $sub_completed) / $all),
                    'num of progress ' => $sub_progress,
                    'percentage progress ' => round((100 * $sub_progress) / $all),
                    'num of missed ' => $sub_missed,
                    'percentage missed ' => round((100 * $sub_missed) / $all),
                    'num of todo ' => $sub_todo,
                    'percentage todo ' => round((100 * $sub_todo) / $all),

                ]);
            }
        }
    }

    public function Achiever()
    {
        /*$s=Subtask::find(28);
        $start = $s->start_at;
        $end = $s->end_at;
        $diff = Carbon::parse($start)->diffInDays(Carbon::parse($end));
        $var = round($diff / 2);
        $da = Carbon::parse($start)->addDay($var);

        if($s->updated_at > $da)
        {

        }
         dd($start,$end,$diff,$var,$da,$s->updated_at,$s->updated_at < $da);
        */


            $all_members = Member::query()->get();
            $ans1 = [];
            $ans2 = [];
            $ans3 = [];
            foreach ($all_members as $member) {
                //get all completed subtask for each member
                $member_subtask = $member->subtask()->where('status_id', '=', Status::Completed)->get();

                $i = 0;

                //check for each subtask
                foreach ($member_subtask as $s) {
                    $start = $s->start_at;
                    $end = $s->end_at;
                    $diff = Carbon::parse($start)->diffInDays(Carbon::parse($end));
                    $var = round($diff / 2);
                    $da = Carbon::parse($start)->addDay($var);

                    if ($s->updated_at < $da) // before half of time
                    {
                        $i++;
                    }
                    //dd($start,$end,$diff,$var,$da,$s->updated_at< $da);
                }

                if (1 < $i && $i <= 5) {
                    $person = DB::table('members')
                        ->join('users', 'members.user_id', 'users.id')
                        ->where('members.id', '=', $member->id)
                        ->get();
                    $ans1[] = $person;
                } else if (5 < $i && $i <= 10) {
                    $person = DB::table('members')
                        ->join('users', 'members.user_id', 'users.id')
                        ->where('members.id', '=', $member->id)
                        ->get();
                    $ans2[] = $person;
                } else if ($i > 10) {
                    $person = DB::table('members')
                        ->join('users', 'members.user_id', 'users.id')
                        ->where('members.id', '=', $member->id)
                        ->get();
                    $ans3[] = $person;
                }

            }
            return \response()->json(['Bronze' => $ans1, 'Silver' => $ans2, 'Golden' => $ans3]);
            //  return \response()->json([$ans3,$ans2,$ans1]);




    }





}
