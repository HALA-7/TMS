<?php

namespace App\Http\Controllers;

use App\Models\MeetingStatus;
use App\Models\Priority;
use App\Models\Role;
use App\Models\Status;
use Illuminate\Http\Request;

class ShowConstantController extends Controller
{
    //meeting status
    public function state1()
    {
        $all=MeetingStatus::query()->get();
        return response()->json($all);
    }

    public function  State1Name(MeetingStatus $id)
    {
        return response()->json($id);
    }

    //task and subtask status
    public function state2()
    {
        $all=Status::query()->get();
        return response()->json($all);
    }

    public function  State2Name(Status $id)
    {
        return response()->json($id);
    }

    //priority
    public function state3()
    {
        $all=Priority::query()->get();
        return response()->json($all);
    }

    public function  State3Name(Priority $id)
    {
        return response()->json($id);
    }

    //roles
    public function state4()
    {
        $all=Role::query()->get();
        return response()->json($all);
    }

    public function  State4Name(Role $id)
    {
        return response()->json($id);
    }
}
