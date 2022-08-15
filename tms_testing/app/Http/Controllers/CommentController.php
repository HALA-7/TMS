<?php

namespace App\Http\Controllers;

use App\Http\Requests\comment\CreateCommentRequest;
use App\Http\Requests\comment\UpdateCommentRequest;
use App\Models\Comment;
use App\Models\Role;
use App\Models\Subtask;
use App\Models\Task;
use App\Models\User;
use App\Notifications\AttachmentAddedNotification;
use App\Notifications\CommentAddedNotification;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use function PHPUnit\Framework\lessThanOrEqual;

class CommentController extends Controller
{

    public function index()
    {
        //
    }


    public function store(CreateCommentRequest $request,Task $task)
    {

        $comment=$task->comments()->create([
            'body'=>$request->body,
            'user_id'=>Auth::id()
        ]);
        // this is for notification
        $sub=Subtask::query()->where('subtasks.task_id','=',$task->id)->get();
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
        $the_data[]=User::query()
            ->where('users.role_id','=',Role::team_leader)
            ->where('users.team_id','=',$task->team_id)
            ->value('users.id');
        $arr=collect($the_data)->unique();

        $notified_user=User::query()->whereIn('users.id',$arr)->get();
        Notification::send( $notified_user, new CommentAddedNotification($comment));

        return response()->json(['message'=>'Added successfully','the comment'=>$comment],201);
    }

    public function update(UpdateCommentRequest $request,Task $task,Comment $comment)
    {
      if(Auth::id()==$comment->user_id)

        {
            $comment->update([
                'body' => $request->body,
                'user_id' => Auth::id()
            ]);

            return response()->json(['updated successfully' => $comment], Response::HTTP_OK);
        }
        else
        {return response()->json(['unauthorized'], 401);}
    }


    public function destroy(Task $task,Comment $comment)
    {
        if(Auth::check() && Auth::id()==$comment->user_id)
        {  $this->authorize('delete',Comment::class);
            $comment->delete();
         return response()->json(['message' => 'deleted successfully'],Response::HTTP_OK);
        }
        else
            return response()->json(['unauthorized'], 401);
    }

    //---------------------SHOW ALL COMMENTS ABOUT SPECIFIC TASKS--------------------------------

    public function show(Request $request,Task $id)
    {
        $comments=DB::table('comments')
                   ->join('users','comments.user_id','users.id')
                 ->select('comments.*','users.first_name','users.last_name')
                  ->where('comments.task_id','=',$id->id)
                 ->get();
        return \response()->json($comments);
    }

    public function ShowOneComment(Comment $id)
    {
        $ans=DB::table('comments')->where('comments.id','=',$id->id)->get();
        return \response()->json(['the comment is:'=>$ans]);
    }

    public function ShowMyComments()
    {
        $ans=DB::table('comments')->where('comments.user_id','=',Auth::id())->get();
        return \response()->json(['the comment is:'=>$ans]);

    }
}
