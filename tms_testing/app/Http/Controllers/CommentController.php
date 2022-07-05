<?php

namespace App\Http\Controllers;

use App\Http\Requests\comment\CreateCommentRequest;
use App\Http\Requests\comment\UpdateCommentRequest;
use App\Models\Comment;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use function PHPUnit\Framework\lessThanOrEqual;

class CommentController extends Controller
{

    public function index()
    {
        //
    }


    public function store(CreateCommentRequest $request,Task $task)
    {
        // same for attachments
        $comment=$task->comments()->create([
            'body'=>$request->body,
            'user_id'=>Auth::id()
        ]);
        return response()->json(['message'=>'Added successfully','the comment'=>$comment],201);
    }


    public function show($id)
    {

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
}
