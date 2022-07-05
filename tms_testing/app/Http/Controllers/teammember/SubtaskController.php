<?php

namespace App\Http\Controllers\teammember;

use App\Http\Controllers\Controller;
use App\Http\Requests\teammember\subtask\UpdateSubtaskRequest;
use App\Models\Subtask;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SubtaskController extends Controller
{

    public function index()
    {
        //
    }


    public function store(Request $request)
    {
        //
    }


    public function show($id)
    {
        //
    }


    public function update(UpdateSubtaskRequest $request,Task $task,Subtask $subtask)
    {
        /*$r=DB::table('users')
            ->join('members','users.id','=','members.user_id')
            ->join('member_subtask','members.user_id','=','member_subtask.member_id')
            ->select('user_id')
            ->get();
        */
        // to get all the member that subtask assigned to it
        $r=DB::table('member_subtask')
            ->join('members','member_subtask.member_id','=','members.id')
            ->join('users','members.user_id','=','users.id')
            ->select('user_id')
            ->where('subtask_id','=',$subtask->id)
            ->get();

        foreach($r as $t)
        {

            if($t->user_id ==Auth::id())
            {
                $subtask->update([
                   'status_id'=>$request->status_id,
                ]);
                return response()->json(['the sub task is updated','the sub task is'=>$subtask]);
               // return response()->json(['done']);
            }


        }
        return response()->json(['you can not do it']);

     //  dd($r,Auth::id());


       /* $r=DB::table('member_subtask')
            ->where('subtask_id','=',$subtask->id)
            ->get();

        foreach($r as $t)
        {



        }
       */

       // dd($r);


      //  dd($subtask->);
       /* $subtask->update([
            'status_id'=>$request->status_id,
        ]);
        return response()->json(['the sub task is updated','the sub task is'=>$subtask]);
       */
    }


    public function destroy($id)
    {
        //
    }
}
