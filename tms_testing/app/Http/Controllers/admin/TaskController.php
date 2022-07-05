<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\admin\task\CreateTaskRequest;
use App\Http\Requests\admin\task\UpdateTaskRequest;
use App\Models\Team;
use App\Models\Task;
use \Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{

    public function index()
    {
        $all_task=Task::query()->get();
        return response()->json($all_task);
    }


    public function store(CreateTaskRequest $request)
    {
        $data= Task::query()->create([
            'title'=>$request->title,
            'description'=>$request->description,
            'start_date'=>$request->start_date,
            'end_date'=>$request->end_date,
           'status_id'=>$request->status_id,
            'team_id'=>$request->team_id
        ]);


       return response()->json(['message'=>'Added successfully',
           'tha task is:'=>$data],201);



    }


   /* public function show($id)
    {

    }*/


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
