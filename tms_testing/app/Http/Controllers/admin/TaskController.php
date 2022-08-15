<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\admin\task\CreateTaskRequest;
use App\Http\Requests\admin\task\UpdateTaskRequest;
use App\Models\Role;
use App\Models\Status;
use App\Models\Team;
use App\Models\Task;
use App\Models\User;
use App\Notifications\TaskNotification;
use \Illuminate\Http\Response;
use Illuminate\Http\Request;
//use Illuminate\Notifications;
//use Illuminate\Notification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;


class TaskController extends Controller
{

    public function store(CreateTaskRequest $request)
    {
        $data= Task::query()->create([
            'title'=>$request->title,
            'description'=>$request->description,
            'start_date'=>$request->start_date,
            'end_date'=>$request->end_date,
         //  'status_id'=>$request->status_id, // to do
            'status_id'=>Status::To_DO,
            'team_id'=>$request->team_id
        ]);

        $notifiable_user=User::query()
                      ->where('role_id','=',Role::team_leader)
                      ->where('team_id','=',$request->team_id)
                       ->get();

       $the_task=Task::query()->where('tasks.id','=',$data->id)->get();

       Notification::send( $notifiable_user, new TaskNotification($the_task));

       return response()->json(['message'=>'Added successfully',
           'tha task is:'=>$data],201);

    }

    public function update(UpdateTaskRequest $request, Task $task)
    {

       $task->update([
            'title'=>$request->title,
            'description'=>$request->description,
            'start_date'=>$request->start_date,
            'end_date'=>$request->end_date,
           'status_id'=>$request->status_id,
            'team_id'=>$request->team_id

        ]);

        return response()->json(['message'=>'Updated successfully',
            'tha task after updated is:'=>$task], Response::HTTP_OK);

    }


    public function destroy(Task $task)
    {
        if(Auth::check())
        {
            $this->authorize('delete',Task::class);
            $task->delete();
            return response()->json(['message'=>'Deleted successfully'],Response::HTTP_OK);
        }

    }




}
