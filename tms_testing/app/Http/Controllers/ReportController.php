<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Status;
use App\Models\Subtask;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use phpseclib3\Crypt\DSA\Formats\Keys\Raw;

class ReportController extends Controller
{
    public function  ShowReport()
    {
        //----------------------------------REPORT FOR ADMIN-----------------------------


        // ARRAY OF ALL TASK AND ITS DETAILS AND PERCENTAGE
        $repo = [];

        $task = (new ShowController())->TaskHelper();
        $all = Subtask::query()->count();

        foreach ($task as $tt) {
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

            //THE PERCENTAGE OF COMPLETED
            $completed = round((100 * $number) / $all);



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
        if (Auth::user()->role_id == Role::team_member) {
            $num = (new ShowController())->SubTaskHelper();
            $all = $num->count();
            $subcomplited = $num->where('status_id', '=', Status::Completed)->count();
            $submissed = $num->where('status_id', '=', Status::Missed)->count();
            $subtodo = $num->where('status_id', '=', Status::To_DO)->count();
            $subonprogrss = $num->where('status_id', '=', Status::On_Progress)->count();

            return response()->json(['total subtasks num ' => $all,
                'num of completed ' => $subcomplited,
                'percentage completed ' => round((100 * $subcomplited) / $all),
                'num of progress ' => $subonprogrss,
                'percentage progress ' => round((100 * $subonprogrss) / $all),
                'num of missed ' => $submissed,
                'percentage missed ' => round((100 * $submissed) / $all),
                'num of todo ' => $subtodo,
                'percentage todo ' => round((100 * $subtodo) / $all),

            ]);
        }
    }


}
