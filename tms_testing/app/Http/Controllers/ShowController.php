<?php

namespace App\Http\Controllers;

use App\Models\MeetingStatus;
use App\Models\Priority;
use App\Models\Status;
use Illuminate\Http\Request;

class ShowController extends Controller
{
    //meeting status
    public function state1()
    {
        $all=MeetingStatus::query()->get();
        return response()->json($all);
    }

    //task and subtask status
    public function state2()
    {
        $all=Status::query()->get();
        return response()->json($all);
    }

    //priority
    public function state3()
    {
        $all=Priority::query()->get();
        return response()->json($all);
    }



}
