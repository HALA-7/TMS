<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\admin\user\CreateUserRequest;
use App\Http\Requests\admin\user\UpdateUserRequest;
use App\Models\Role;
use \Illuminate\Http\Response;
use App\Models\Team;
use App\Models\User;
use Cassandra\Exception\UnpreparedException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    //
    public function index()
    {
        $all_user=User::query()->get();
        return response()->json($all_user);
    }

    //to create the user
    public function store(CreateUserRequest $request)
    {  $t=$request->team_id;
        $gg= DB::table('users')->where('team_id','=',$t)
                                      ->where('role_id','=',2)
                                       ->value('team_id');

     if($request->role_id==Role::team_leader)
     {
         if (!$gg)  // $gg=null ==> !$gg=true
         {  //'we will create the leader because there is no leader for this team');
             $user_data=User::query()->create([
                 'first_name'=>$request->first_name,
                 'last_name'=>$request->last_name,
                 'email'=>$request->email,
                 'employee_identical'=>$request->employee_identical,
                 'password'=>bcrypt($request->password),
                 'role_id'=>$request->role_id,
                 'team_id'=>$request->team_id
             ]);
             return response()->json(['message'=>'new user is added','the user is:'=>$user_data],Response::HTTP_OK);

         }
         else // $gg= true ==> $gg=false
         {
             return \response(['message' => 'this team  has a leader']);
         }

     }
     else {

             $user_data=User::query()->create([
               'first_name'=>$request->first_name,
             'last_name'=>$request->last_name,
            'email'=>$request->email,
            'employee_identical'=>$request->employee_identical,
            'password'=>bcrypt($request->password),
            'role_id'=>$request->role_id,
            'team_id'=>$request->team_id
            ]);


             return response()->json(['message'=>'new user is added','the user is:'=>$user_data],Response::HTTP_OK);
        }
    }

    //to specific user
  /*  public function show($id)
    {

    }
*/
    // you send the user that you want to update
    public function update(UpdateUserRequest $request, User $user)
    {   $temp=DB::table('users')
        ->where('role_id','=',2)
        ->where('team_id','=',$request->team_id)
        ->value('id');

        $temp2=DB::table('users')
            ->where('role_id','=',2)
            ->where('team_id','=',$request->team_id)
            ->value('team_id');

        // in this case you want to swap,you want to change the leader
         if($user->id!=$temp && $request->role_id==2 && $request->team_id==$temp2)
         {   //dd('ok');
             $tt= DB::table('users')
             ->where('id','=',$temp)
             ->update(['role_id'=>Role::team_member]);

          //  $d= DB::table('leaders')->where('user_id', '=', $temp)->delete();
           // dd($d);
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
             $d= DB::table('members')->where('user_id', '=', $dd)->delete();
            // dd($temp,$dd);
             return response()->json(['message' => 'Updated successfully', 'the user is:' => $user], Response::HTTP_OK);

         }

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

   // to delete user
    public function destroy(User $user)
    {
        if(Auth::check())
        {
            $this->authorize('delete',User::class);
            $user->delete();
            return response()->json(['message'=>'Deleted successfully'],Response::HTTP_OK);
        }

    }
}
