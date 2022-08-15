<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\admin\user\CreateUserRequest;
use App\Http\Requests\admin\user\UpdateUserRequest;
use App\Mail\TestSentMail;
use App\Models\Leader;
use App\Models\Member;
use App\Models\Role;
use \Illuminate\Http\Response;
use App\Models\Team;
use App\Models\User;
use Cassandra\Exception\UnpreparedException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{

    //--------------------TO CREATE USER-------------------
    public function store(CreateUserRequest $request)
    {  $team_name=$request->team_id;
        //TO GET THE USER (LEADER) IF THERE ARE
        $gg= DB::table('users')->where('team_id','=',$team_name)
                                      ->where('role_id','=',Role::team_leader)
                                       ->value('team_id');

      //---------WHEN CREATE A LEADER USER--------------
     if($request->role_id==Role::team_leader)
     {   //'we will create the leader because there is no leader for this team'
         if (!$gg)  // $gg=null ==> !$gg=true
         {
             $user_data=User::query()->create([
                 'first_name'=>$request->first_name,
                 'last_name'=>$request->last_name,
                 'email'=>$request->email,
                 'employee_identical'=>$request->employee_identical,
                 'password'=>bcrypt($request->password),
                 'role_id'=>$request->role_id,
                 'team_id'=>$request->team_id
             ]);

          // SEND EMAIL THAT CONTAIN PASSWORD TO USER
            $mt=Mail::to($request->email)->send(new TestSentMail($request->password));

             return response()->json(['message'=>'new user is added',
                 'the user is:'=>$user_data,
                 'the mail is sent to user:'=>$mt],Response::HTTP_OK);

         }

         else // $gg= true ==> $gg=false
         {
             return \response(['message' => 'this team  has a leader']);
         }

     }
     //------------WHEN CREATE USER MEMBER------------------
     else {

             $user_data = User::query()->create([
               'first_name'=>$request->first_name,
             'last_name'=>$request->last_name,
            'email'=>$request->email,
            'employee_identical'=>$request->employee_identical,
            'password'=>bcrypt($request->password),
            'role_id'=>$request->role_id,
            'team_id'=>$request->team_id
            ]);

        // TO SEND EMAIL THAT CONTAIN PASSWORD TO USER
         $mt=Mail::to($request->email)->send(new TestSentMail($request->password));

         return response()->json(['message'=>'new user is added',
             'the user is:'=>$user_data,
             'the mail is sent to user:'=>$mt],Response::HTTP_OK);
        }
    }


    //-------------------------------UPDATE THE USER------------------------------

    public function update(UpdateUserRequest $request, User $user)
    {
        //give me the user id (check if the team has a leader)
        $temp=DB::table('users')
        ->where('role_id','=',Role::team_leader)
        ->where('team_id','=',$request->team_id)
        ->value('id');

        //give me the user team that you want to update
        $temp2=DB::table('users')
            ->where('role_id','=',Role::team_leader)
            ->where('team_id','=',$request->team_id)
            ->value('team_id');

        // in this case you want to swap,you want to change the leader
         if($user->id!=$temp && $request->role_id==2 && $request->team_id==$temp2)
         {
             $tt= DB::table('users')
             ->where('id','=',$temp)
             ->update(['role_id'=>Role::team_member]);


             $user->update([
                 'first_name'=>$request->first_name,
                 'last_name'=>$request->last_name,
                 'email'=>$request->email,
                 'employee_identical'=>$request->employee_identical,
                 'password'=>bcrypt($request->password),
                 'role_id'=>$request->role_id,
                 'team_id'=>$request->team_id
             ]);
             $dd=$user->id;
             $d= Member::where('user_id', '=', $dd)->delete();
             $b=Leader::where('user_id', '=', $temp)->delete();

            return response()->json(['message' => 'Updated successfully', 'the user is:' => $user], Response::HTTP_OK);

         }

         //UPDATE THE USER NORMALLY
         else {

             $user->update([
                 'first_name' => $request->first_name,
                 'last_name' => $request->last_name,
                 'email' => $request->email,
                 'employee_identical' => $request->employee_identical,
                 'password' => bcrypt($request->password),
                 'role_id' => $request->role_id,
                 'team_id' => $request->team_id
             ]);
             return response()->json(['message' => 'Updated successfully', 'the user is:' => $user], Response::HTTP_OK);
         }// end else
    }


    // TO DELETE THE USER
    public function destroy(User $user)
    {
        if(Auth::check())
        {
            $this->authorize('delete',User::class);
            $user->delete();
            return response()->json(['message'=>'Deleted successfully'],Response::HTTP_OK);
        }

    }


    // TO SHOW THE USER THAT I CREATE WITH THE BASIC INFO
    public function  ShowUsers()
    {
        if(Auth::check())
        {
            $this->authorize('viewAny',User::class);
            $all_user=User::query()->where('users.id','>','1')->get();
            return response()->json(['the users'=>$all_user]);

        }


    }


    // TO SHOW THE INFORMATION THAT THE USER ADD AFTER HE/SHE ENTER TO THE APPLICATION
    public  function  ShowUser(User $user)
    {
        if(Auth::check())
        {
            $this->authorize('view',$user);

            // the user is leader
            if($user->role_id==Role::team_leader)
            {
                $the_user = DB::table('leaders')
                    ->where('user_id', '=', $user->id)
                    ->get();
            }
            //the user is member
            else {
                $the_user = DB::table('members')
                    ->where('user_id', '=', $user->id)
                    ->get();
            }

            return response()->json(['the user'=>$the_user],Response::HTTP_OK);
        }
    }


    //ALL INFO ABOUT ALL USERS
    public function ShowUsersDetails()
    {
        if(Auth::check())
        {
            $this->authorize('viewAny',User::class);

            // the user is leader

               $all_leaders = DB::table('users')
                    ->join('leaders', 'users.id', '=', 'leaders.user_id')
                   // ->select('leaders.*','users.*')
                    ->get();

            //the user is member

                $all_members = DB::table('users')
                    ->join('members', 'users.id', '=', 'members.user_id')
                   // ->select('members.*','users.*')
                    ->get();

          return response()->json([$all_leaders,$all_members],Response::HTTP_OK);
        }
    }



    //ALL INFO(BASIC AND ADDITIONAL) ABOUT SPECIFIC USER
    public function show(User $user)
    {
        if(Auth::check())
        {
            $this->authorize('viewAny',User::class);

            //return DB::table('users')->where('users.id','=',$user->id)->get();

            if($user->role_id==Role::team_leader)
            $show_user=DB::table('users')
                ->join('leaders','users.id','=','leaders.user_id')
                //->select('')
                 ->where('users.id','=',$user->id)
                ->get();

            else if($user->role_id==Role::team_member)
                $show_user=DB::table('users')
                    ->join('members','users.id','=','members.user_id')
                   // ->select('users.*','members.*')
                    ->where('users.id','=',$user->id)
                    ->get();



            return response()->json($show_user);

        }
    }

}
