<?php

namespace App\Http\Controllers\teammember;

use App\Http\Controllers\Controller;
use App\Http\Requests\teammember\subtask\UpdateSubtaskRequest;
use App\Models\Role;
use App\Models\Status;
use App\Models\Subtask;
use App\Models\Task;
use App\Models\User;
use App\Notifications\SubTaskUpdatedNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class SubtaskController extends Controller
{

    public function update(UpdateSubtaskRequest $request,Task $task,Subtask $subtask)
    {
        // dd($task->id==$subtask->task_id);
        // to get all the member that subtask assigned to it
        $r = DB::table('member_subtask')
            ->join('members', 'member_subtask.member_id', '=', 'members.id')
            ->join('users', 'members.user_id', '=', 'users.id')
            ->select('user_id')
            ->where('subtask_id', '=', $subtask->id)
            ->get();

        foreach ($r as $t)
        {

            // if this subtask for this user
            if ($t->user_id == Auth::id() && $task->id==$subtask->task_id)
            {
                if ($subtask->status_id==Status::Missed)
                {
                    return response()->json(['message' => 'not allowed this subtask is missed']);
                }

                else
                {
                    $subtask->update(['status_id' => $request->status_id,]);
                    $led=User::query()
                        ->where('role_id','=',Role::team_leader)
                        ->where('team_id','=',Auth::user()->team_id)
                        ->get();
                    Notification::send($led,new SubTaskUpdatedNotification($subtask));
                    return response()->json(['the sub task is updated', 'the sub task is' => $subtask]);
                }

            }


        }
        return response()->json(['you can not do it']);

    }


}
