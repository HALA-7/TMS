<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use \Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TestController extends Controller
{

    public function  show()
    {
        //method 1 using Query Builder
    // $users=DB::table('users')->get();

       // foreach ($users as $user) {
         //   echo $user->id.' '.
           // $user->first_name;
       // }
    //  return response(['all user'=>$user], Response::HTTP_OK);
   //--------------------------------------------------------
      //method2
       //   $user=User::query()->get()->all();
       // return response($user, Response::HTTP_OK);
        //---------------------------------------------------
       // $users=DB::table('users')->where('team_id','1')->first();
          //return $users->colunm name that you want
      //-----------------------------------------------------
    //    $users=DB::table('users')->where('first_name','hala')->value('email','last_name');
        //------------------------
       // $user=DB::table('users')->find(id: 1)->get(); not work
     //   $userIds = [1,2,6];
       // $user=User::find($userIds );
        // return $user;
        //------------------------------------------

      //  $users=DB::table('users')->pluck('first_name');
        //-----------------------------
       // $users=DB::table('users')->where('team_id','1')->get();
        //-------------------------

      //  $users = DB::table('users')->count();
     //  if( DB::table('users')->where('first_name', 'jojo')->exists())
       //return \response( ['message'=>'test'], Response::HTTP_OK);
       //else
         //  return \response( ['message'=>'NO'], Response::HTTP_OK);
  //---------------------------------------
        //BY USING SELECT STATEMENT
     //   $users = DB::table('users')
       //     ->select('first_name', 'email')
         //   ->get();
        //---------------------------------
    //    $users = DB::table('users')
      //  ->orderByRaw('updated_at - created_at DESC')
        //    ->get();
  //---------------------------------------------
       // $users=DB::table('users')
         //   ->orderBy('first_name','desc')
           // ->get();
//-------------------------------
    //   $user=User::find(3);
      // $gg=$user->attachments()->select('title')->get();
      //  where('user_id','2')->get();
       // dd($gg);
 //        $task=Task::find(1);
   //     $gg=$task->subtasks()->get();
      //  $gg=Task::has('attachments','>','1')->get();
      //  $gg=Task::withSum('attachments','comments')->get();
        //    dd($gg);
      // foreach($gg as $i )
        //{  //$post->comments_sum_votes
          //  dd($i->attachments_sum_comments);
        //}
//dd($gg->comments_count);

     //   $gg=Task::select('title')->withcount('attachments')->get();
       //return \response( $gg, Response::HTTP_OK);

      //  foreach($tests as $test)
        //{echo }
       // dd($test->members()->get());
       // $tests=Task::with('subtask.title')->get();
       // dd($tests);


        $test=DB::table('statuses')->get();
        return \response( $test, Response::HTTP_OK);
      //  $test2=DB::table('teams')->get();
        //$test3=DB::table('Meetings')->get();
        //$test4=DB::table('Tasks')->get();



    }
}
