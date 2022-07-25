<?php

namespace App\Http\Controllers;

use App\Http\Requests\attachment\CreateAttachmentRequest;
use App\Http\Requests\attachment\UpdateAttachmentRequest;
use App\Models\Attachment;
use App\Models\Role;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AttachmentController extends Controller
{



    public function store(CreateAttachmentRequest $request,Task $task)
    {
       // if the leader add this attachment to this task
        if(Auth::id()==Role::team_leader) {
            $tt = DB::table('tasks')
                ->join('teams', 'tasks.team_id', '=', 'teams.id')
                ->join('users', 'teams.id', '=', 'users.team_id')
                ->join('leaders','users.id','=','leaders.user_id')
                ->where('role_id', '=', Role::team_leader)
                ->where('tasks.id', '=', $task->id)
                ->get();
          //  dd($tt);
        }
        else {
            $tt = DB::table('tasks')
                ->join('subtasks', 'tasks.id', '=', 'subtasks.task_id')
                ->join('member_subtask', 'subtasks.id', '=', 'member_subtask.subtask_id')
                ->join('members', 'member_subtask.member_id', '=', 'members.id')
                ->join('users', 'members.user_id', '=', 'users.id')
                ->select('user_id')
                ->where('task_id', '=', $task->id)
                ->get();
        }

        foreach($tt as $h)
        {
           if($h->user_id==Auth::id())
            {
               // dd('ok');
               // echo $h->user_id;
                if($request->hasFile('file'))
                {
                    $filename = time() . '_' . $request->file('file')->getClientOriginalName();
                    $filepath = $request->file('file')->storeAs('uploads', $filename, 'public');
                    // store the file that i uploaded in storage\app\public\uploads

                    $data = $task->attachments()->create([
                        'title' => $request->title,
                        'description' => $request->description,
                        'FileName' => time() . '_' . $request->file('file')->getClientOriginalName(),
                        'FilePath' => '/storage/' . $filepath,
                        'user_id'=>Auth::id()

                    ]);
                    return response()->json(['message'=>'added  successfully with file ','the info:'=>$data],201);
                }// for file

                else //there is no file
                {  $data = $task->attachments()->create([
                    'title' => $request->title,
                    'description' => $request->description,
                    'user_id'=>Auth::id()

                ]);
                    return response()->json(['message'=>'Added successfully ','the info:'=>$data],201);
                }

            }//for authorization

        }

       return response()->json(['message'=>'you are not authorize to add attachment to this task'],Response::HTTP_OK);

    }
    public function update(UpdateAttachmentRequest $request, Task $task,Attachment $attachment)
    {
       //it allow to update the
       if(Auth::id()==$attachment->user_id)
       {
           if ($request->hasFile('file'))
           {
               $filename = time() . '_' . $request->file('file')->getClientOriginalName();
               $filepath = $request->file('file')->storeAs('uploads', $filename, 'public');


               $attachment->update([
                   'title' => $request->title,
                   'description' => $request->description,
                   'FileName' => time() . '_' . $request->file('file')->getClientOriginalName(),
                   'FilePath' => '/storage/' . $filepath,
                   'user_id' => Auth::id()

               ]);
           } //for if

           else {
               $attachment->update([
                   'title' => $request->title,
                   'description' => $request->description,
                   'user_id' => Auth::id()

               ]);

           }//for else
           return response()->json(['Updated successfully '=>$attachment],Response::HTTP_OK);
       }//for check
        else
        return response()->json(['unauthorized'],401);
    }


    public function destroy(Task $task,Attachment $attachment)
    {
        if(Auth::check() && Auth::id()==$attachment->user_id)
        {  $this->authorize('delete',Attachment::class);
            $attachment->delete();
            return response()->json(['message' => 'attachment is deleted']);
        }
        else
            return response()->json(['unauthorized'], 401);

    }

    //----------------------------------SHOW ATTACHMENT------------------------

    public function show(Request $request,Task $id)
    {
        $attachments=$id->attachments()->get();

        return \response()->json($attachments);
    }
}
