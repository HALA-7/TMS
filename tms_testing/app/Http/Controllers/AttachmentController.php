<?php

namespace App\Http\Controllers;

use App\Http\Requests\attachment\CreateAttachmentRequest;
use App\Http\Requests\attachment\UpdateAttachmentRequest;
use App\Models\Attachment;
use App\Models\Role;
use App\Models\Subtask;
use App\Models\Task;
use App\Models\User;
use App\Notifications\AttachmentAddedNotification;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class AttachmentController extends Controller
{



    public function store(CreateAttachmentRequest $request,Task $task)
    {
       // if the leader add this attachment to this task
        if(Auth::user()->role_id==Role::team_leader)
        {
            $tt = DB::table('tasks')
                ->join('teams', 'tasks.team_id', '=', 'teams.id')
                ->join('users', 'teams.id', '=', 'users.team_id')
                ->join('leaders','users.id','=','leaders.user_id')
                ->where('role_id', '=', Role::team_leader)
                ->where('tasks.id', '=', $task->id)
                ->get();
         // dd(Auth::id());
        }
        else if(Auth::user()->role_id==Role::team_member)
        {
            $tt = DB::table('tasks')
                ->join('subtasks', 'tasks.id', '=', 'subtasks.task_id')
                ->join('member_subtask', 'subtasks.id', '=', 'member_subtask.subtask_id')
                ->join('members', 'member_subtask.member_id', '=', 'members.id')
                ->join('users', 'members.user_id', '=', 'users.id')
                ->select('user_id')
                ->where('task_id', '=', $task->id)
                ->get();

        }
        else{
            return response()->json(['admin not auth to add attachments']);
        }

        // get all subtask for this task
        $sub=Subtask::query()->where('subtasks.task_id','=',$task->id)->get();


        foreach($tt as $h)
        {
           if($h->user_id==Auth::id())
            {

                $data = $task->attachments()->create([
                    'title' => $request->title,
                    'description' => $request->description,
                    'user_id'=>Auth::id()]);


                //this code to know who the user that will receive the notification
                $the_data=[];
                foreach ($sub as $one_sub)
                {
                    $get_mem=$one_sub->members()->get();
                    foreach ($get_mem as $one_user)
                    {
                        $not_user=User::query()
                            ->where('users.id','=',$one_user->user_id)
                            ->value('users.id');
                        $the_data[] = $not_user;

                    }

                }

                $arr=collect($the_data)->unique();
                $notified_user=User::query()->whereIn('users.id',$arr)->get();
                Notification::send( $notified_user, new AttachmentAddedNotification($data));

                return response()->json(['message'=>'Added successfully ','the info:'=>$data],201);


            }//for authorization

        }

       return response()->json(['message'=>'you are not authorize to add attachment to this task'],Response::HTTP_OK);

    }

    public function update(UpdateAttachmentRequest $request, Task $task,Attachment $attachment)
    {
       //it allow to update the
       if(Auth::id()==$attachment->user_id)
       {

           $attachment->update([
                   'title' => $request->title,
                   'description' => $request->description,
                   'user_id' => Auth::id()
               ]);

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

    //----------------------------------SHOW ATTACHMENTS For specific task------------------------
    public function show(Request $request,Task $id)
    {
        $attachments=$id->attachments()->get();

        return \response()->json($attachments);
    }

    public function ShowOne(Attachment $id)
    {
        return \response()->json(['the attachment' => $id]);
    }

    public function ShowMyAttachment()
    {
        $ans=DB::table('attachments')->where('attachments.user_id','=',Auth::id())->get();
        return \response()->json(['my attachments'=>$ans]);
    }

}
