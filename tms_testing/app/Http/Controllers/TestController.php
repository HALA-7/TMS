<?php

namespace App\Http\Controllers;

use App\Http\Requests\teammember\subtask\UpdateSubtaskRequest;
use App\Mail\TestSentMail;
use App\Models\Member;
use App\Models\Status;
use App\Models\Subtask;
use App\Models\Task;
use App\Models\User;
use App\Notifications\AttachmentAddedNotification;
use App\Notifications\SubTaskAddedNotification;
use App\Notifications\SubTaskRemenderNotification;
use Carbon\Carbon;
use \Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

use Illuminate\Support\Facades\Storage;
use phpDocumentor\Reflection\Element;
use function PHPUnit\Framework\lessThanOrEqual;
use Illuminate\Support\Facades\Notification;

class TestController extends Controller
{

   /*public function test()
   {
       $subtask=Subtask::query()->get();
       foreach($subtask as $s)
       {
           $show_task=DB::table('tasks')
               //->select('end_date')
               ->where('tasks.id','=',$s->task_id)
               ->value('end_date');

           // subtask rich end-date                 not completed                          task not end yet
           if(\Carbon\Carbon::now() > $s->end_at && $s->status_id != Status::Completed && $show_task >Carbon::now())
           {   //{$subtask->update(['status_id' => Status::Late]);}
             //  echo 'make subtask as late and the subtask is'.$s->id."<br>";
           }
           // subtask rich end-date                 not completed                          task end
           else if(\Carbon\Carbon::now() > $s->end_at && $s->status_id != Status::Completed && $show_task <Carbon::now())
           {
               {$subtask->update(['status_id' => Status::Missed]);}
              // echo 'make subtask as missed so cannot update it'."<br>";
           }
           else
           {
             echo 'no need to update any thing'."<br>";
           }



       }
      //
   }*/
    public function testachivment()
    {
       /* $all_members = Member::query()->get();
        $ans=[];
        foreach ($all_members as $member)
        {
            $member_subtask = $member->subtask()->where('status_id','=',Status::Completed)->count();
            $i=0;

            foreach ($member_subtask as $s)
            {
                $start = $s->start_at;
                $end = $s->end_at;
                $diff = Carbon::parse($start)->diffInDays(Carbon::parse($end));
                $var = round($diff / 2);
                $da = Carbon::parse($start)->addDay($var);

                if ($s->updated_at < $da) // before half of time
                {
                   $i++;
                }
                //dd($start,$end,$diff,$var,$da,$s->updated_at< $da);
           }

            if($i>10)
            {  $person=DB::table('members')
                       ->join('users','members.user_id','users.id')
                       ->get();
                $ans=[$person];
            }




        }

        return \response()->json([$ans]);
*/
        /* $subtask=Subtask::query()->get();

         foreach($subtask as $s)
         {
             $start=$s->start_at;
             $end=$s->end_at;


             $diff=Carbon::parse($start)->diffInDays(Carbon::parse($end));
             $var=round($diff/2);

             $da=Carbon::parse($start)->addDay($var);

             if($s->updated_at < $da) // before half of time
             {
                 echo $s->id;
             }
             //dd($start,$end,$diff,$var,$da,$s->updated_at< $da);
         }

     }*/
    }

    public function test()
    {
        /*$s=Subtask::find(28);
        $start = $s->start_at;
        $end = $s->end_at;
        $diff = Carbon::parse($start)->diffInDays(Carbon::parse($end));
        $var = round($diff / 2);
        $da = Carbon::parse($start)->addDay($var);
*/
        //if($s->updated_at > $da)
        //{

        //}
       // dd($start,$end,$diff,$var,$da,$s->updated_at,$s->updated_at < $da);

       $all_members = Member::query()->get();
        $ans1=[];$ans2=[];$ans3=[];
        foreach ($all_members as $member)
        {
            //get all completed subtask for each member
            $member_subtask = $member->subtask()->where('status_id','=',Status::Completed)->get();

            $i=0;

            //check for each subtask
            foreach ($member_subtask as $s)
            {
                $start = $s->start_at;
                $end = $s->end_at;
                $diff = Carbon::parse($start)->diffInDays(Carbon::parse($end));
                $var = round($diff / 2);
                $da = Carbon::parse($start)->addDay($var);

                if ($s->updated_at < $da) // before half of time
                {
                    $i++;
                }
                //dd($start,$end,$diff,$var,$da,$s->updated_at< $da);
            }

            if(1<$i && $i<=5)
            {
                $person=DB::table('members')
                ->join('users','members.user_id','users.id')
                ->where('members.id','=',$member->id)
                ->get();
               $ans1[]=$person;
            }

            else if(5<$i && $i<=10)
            {
                $person=DB::table('members')
                    ->join('users','members.user_id','users.id')
                    ->where('members.id','=',$member->id)
                    ->get();
                $ans2[]=$person;
            }
            else if($i>10)
            {
                $person=DB::table('members')
                    ->join('users','members.user_id','users.id')
                    ->where('members.id','=',$member->id)
                    ->get();
                $ans3[]=$person;
            }

        }
        return \response()->json(['Bronze'=>$ans1,'Silver'=>$ans2,'Golden'=>$ans3]);
      //  return \response()->json(['Golden'=>$ans3,'Silver'=>$ans2,'Bronze'=>$ans1]);





    }

    public function TestingImg(Request $request)
    {
        if ($request->hasFile('profile')) {
            $file = $request->file('profile');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;

            // 1: this will save in public
            $file->move('images',$filename);
            $link=asset('images/'.$filename);
            $tt=substr($link,-21);
            //------------------------------------------------
            dd($tt);

            // dd($tt);
            // 2: will store in storage file
          //  $link=$file->storeAs('public/images/users',$filename);

            // $file->move('public/image', $filename); //it will store the image in public folder
            // $file->move(public_path('images'),$filename); //will store in public path
            //$ii=$file->storeAs('public/image',$filename);//will store in storage
            // $link=asset('public/image/'.$filename);
            //$image_temp = $link;
           // $image_temp=Storage::disk('public')->url($ii);
            //  dd($image_temp);
            //$image_temp = $filename;
            //dd($link);
            return \response()->json($link);
        }
        else{
            dd('ok');
        }

    }





}
















