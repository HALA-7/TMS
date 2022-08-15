<?php

namespace App\Http\Controllers\teamleader;

use App\Http\Controllers\Controller;
use App\Http\Requests\teamleader\subtask\CreateSubtaskRequest;
use App\Http\Requests\teamleader\subtask\UpdateSubtaskRequest;
use App\Models\Meeting;
use App\Models\Status;
use App\Models\Subtask;
use App\Models\Task;
use App\Models\User;
use App\Notifications\SubTaskAddedNotification;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class SubtaskController extends Controller
{

    public function store(CreateSubtaskRequest $request,Task $task)
    {

        //Test if the user can create the subtask for this task
        if($task->team_id==Auth::user()->team_id)
        {

             $data= $task->subtasks()->create([
            'title'=> $request->title,
            'description'=>$request->description,
            'start_at'=>$request->start_at,
            'end_at'=>$request->end_at,
            'priority_id'=>$request->priority_id,
            'status_id'=>$request->status_id,
          //  'task_id'=>$request->task_id
        ]);

        foreach ($request->user_list as $i)
        {
            $data->members()->attach($i);
        }

        $temp_member=$data->members()->get();

        $info_to_send=Subtask::query()->where('subtasks.id','=',$data->id)->get();

        foreach ($temp_member as $temp_user)
        {
            $not_user=User::query()->where('users.id','=',$temp_user->user_id)->get();
            Notification::send($not_user, new SubTaskAddedNotification($info_to_send));
        }


        return response()->json(['message'=>'subtask is added','the info:'=>$data,
            'assigned to:'=>$data->members()->get()],201);


        }
        else
        {
            return response()->json(['message'=>'can not add subtask to this task']);
        }

    }



    public function update(UpdateSubtaskRequest $request,Task $task,Subtask $subtask)
    {

        if($task->team_id==Auth::user()->team_id && $task->id==$subtask->task_id)
        { //dd('you can update');
            $subtask->update([
                'title'=> $request->title,
                'description'=>$request->description,
                'start_at'=>$request->start_at,
                'end_at'=>$request->end_at,
                'priority_id'=>$request->priority_id,
                'status_id'=>$request->status_id,
                //'task_id'=>$request->task_id
            ]);

            foreach ($request->user_list as $i) {
                $temp_array[]=$i;
            }

            //by using this method we can add new user and update the exists users
            $subtask->members()->sync($temp_array);

            return response()->json(['message'=>'subtask is updated','the info:'=>$subtask,
                'assigned to:'=>$subtask->members()->get()],Response::HTTP_OK);

        }
        else{
            return response()->json(['message'=>'can not update subtask to this task']);
        }




    }


    public function destroy(Task $task,Subtask $subtask)
    {
        if(Auth::check())

         { $this->authorize('delete', Subtask::class);
           if($task->team_id==Auth::user()->team_id && $task->id==$subtask->task_id)
           {
             $subtask->delete();
             return response()->json(['message' => 'the subtask is deleted'], Response::HTTP_OK);
           }

         return response()->json(['message' => 'you can not delete this subtask'], Response::HTTP_OK);
         }

        return response()->json(['message' => 'unauthorized'], 403);

    }


    public function UpdateTheTask(Request $request,Task $id)
    {
        if(Auth::user()->role_id==2 &&Auth::user()->team_id==$id->team_id && $id->status_id==Status::To_DO
            && $request->status_id==Status::On_Progress)
        {
            $request->validate([
                'status_id' => ['required']
            ]);
            $id->update(['status_id' => $request->status_id]);
            return response()->json(['message' => 'update successfully'], Response::HTTP_OK);
        }
        else
            return response()->json(['message' => 'can not update'], Response::HTTP_OK);
    }



}
