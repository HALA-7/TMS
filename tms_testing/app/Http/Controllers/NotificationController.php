<?php

namespace App\Http\Controllers;

use App\Models\Status;
use App\Models\Subtask;
use App\Models\User;
use App\Notifications\SubTaskRemenderNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class NotificationController extends Controller
{
    //---------------------------THIS API TO SHOW THE NOTIFICATION------------------------------
    public function ShowMyNotification()

    {
        $subtask=Subtask::query()->get();
        foreach ($subtask as $su)
        {
            //this specific code to send reminder notification
            $dif=Carbon::parse($su->end_at)->diffInDays(Carbon::parse(Carbon::now()));

            if( $dif==1 && $su->status_id != Status::Completed)
            {
                $sub_task_member=$su->members()->where('subtask_id','=',$su->id)->get();
                if(!($su->test))
                {
                    foreach ($sub_task_member as $stm)
                    {
                        $not_user=User::query()->where('users.id','=',$stm->user_id)->get();
                        Notification::send($not_user,new SubTaskRemenderNotification($su));
                        $su->update(['test'=>true]);
                    }
                }

            }

        }

        $my_notifications=auth()->user()->notifications()->get();
        return \response()->json(['my notification'=>$my_notifications]);
    }

    //-------------TO MAKE SPECIFIC NOTIFICATION AS READ--------------------
    public function ReadMyNotification($id)
    {
        if(Auth::check()) {
            $notification = Auth::user()->notifications()->where('id', '=', $id)->get();

            if ($notification) {
                $notification->markAsRead();
                return \response()->json(['message' => 'added to read notification']);
            }
        }
    }

    //----------THIS API TO SHOW THE NOTIFICATION THAT THE USER READ IT--------------------------
    public function ReadNotification()
    {
        if(Auth::check()){
            $read=Auth::user()->notifications()->where('read_at', '<>', null)
                ->where('notifiable_id','=',Auth::id())
                ->get();
            return \response()->json(['the notification that you read' => $read]);
        }

    }
}
