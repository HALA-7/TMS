<?php

namespace App\Http\Controllers;

use App\Models\Status;
use App\Models\Subtask;
use App\Models\Task;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;

class FilteringController extends Controller
{
    public function  CompletedTask()
    {
        $this->authorize('TaskStatus',Task::class);
         $result = (new ShowController())->TaskHelper()->
         where('status_id','=',Status::Completed);

          return response()->json(['the completed task'=>$result]);
    }

    public function  OnProgressTask()
    {
        $this->authorize('TaskStatus',Task::class);
        $result = (new ShowController())->TaskHelper()->
        where('status_id','=',Status::On_Progress);

        return response()->json(['on progress task'=>$result]);
    }

    public function  MissedTask()
    {
        $this->authorize('TaskStatus',Task::class);
        $result = (new ShowController())->TaskHelper()->
        where('status_id','=',Status::Missed);

        return response()->json(['missed task'=>$result]);
    }

    public function  To_DoTask()
    {
        $this->authorize('TaskStatus',Task::class);
        $result = (new ShowController())->TaskHelper()->
        where('status_id','=',Status::To_DO);

        return response()->json(['to doing task'=>$result]);
    }

    //-----------------------------------SUB TASK---------------------------------

    public function  CompletedSubTask()
    {
        $this->authorize('SubStatus',Subtask::class);
        $result=(new ShowController())->SubTaskHelper()
             ->where('status_id','=',Status::Completed);

         return response()->json(['the completed sub task'=>$result]);
    }

    public function  MissedSubTask()
    {

        $this->authorize('SubStatus',Subtask::class);
        $result=(new ShowController())->SubTaskHelper()
            ->where('status_id','=',Status::Missed);

        return response()->json(['the missed sub task'=>$result]);

    }

    public function  ProgressSubTask()
    {
        $this->authorize('SubStatus',Subtask::class);
        $result=(new ShowController())->SubTaskHelper()
            ->where('status_id','=',Status::On_Progress);

        return response()->json(['on progress sub task'=>$result]);
    }

    public function  ToDoSubTask()
    {
        $this->authorize('SubStatus',Subtask::class);
        $result=(new ShowController())->SubTaskHelper()
            ->where('status_id','=',Status::To_DO);

        return response()->json(['on progress sub task'=>$result]);
    }

}
