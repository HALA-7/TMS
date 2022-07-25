<?php

namespace App\Http\Controllers\teamleader;

use App\Http\Controllers\Controller;
use App\Http\Requests\teamleader\subtask\CreateSubtaskRequest;
use App\Http\Requests\teamleader\subtask\UpdateSubtaskRequest;
use App\Models\Subtask;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SubtaskController extends Controller
{

    public function store(CreateSubtaskRequest $request,Task $task)
    {
        /* $testing=DB::table('users')
                  ->join('members','users.id','=','members.user_id')
                   ->select('members.id')
                    ->where('users.team_id','=',Auth::user()->team_id)
                   ->get();
             dd($testing);*/

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
         else
         return response()->json(['message' => 'you can not delete this subtask'], Response::HTTP_OK);
         }

        return response()->json(['message' => 'unauthorized'], 403);

    }



}
