<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\admin\team\StoreTeamRequest;
use App\Http\Requests\admin\team\UpdateTeamRequest;
use App\Models\Role;
use App\Models\Team;
use Cassandra\UuidInterface;
use \Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class TeamController extends Controller
{
    // to show all team
    public function index()
    {
       if(Auth::check())
       {
           $this->authorize('viewAny',Team::class);
           $all_team=Team::query()->get();
           return response()->json($all_team);
       }
    }


    public function store(StoreTeamRequest $request)
    {
        $data= Team::query()->create([
            'name'=>$request->name
        ]);
        return response()->json(['message' => 'Added successfully',
            'tha department:'=>$data],
            201);

    }

    public function update(UpdateTeamRequest $request, Team $id)
    {
         $id->update([
            'name'=>$request->name
        ]);
        return response()->json(['message' => 'Updated successfully',$id], Response::HTTP_OK);
    }


    //to show each team and its details
    public function show(Team $id)
    {      /*  $gg=$task->subtasks()->get();
       $data_team=Team::find($id->id);
        $data=$data_team->members()->get();
        dd($data);
        foreach($data as $i)
        {
           dd($i);
        }
        return response()->json(['message' => 'Updated successfully',$id]);*/
        //$test=Team::with('leader','members')->get();
        //return response()->json(['message' => $test]);
       // $data_team=Team::find($id->id);
        //$d1=$data_team->members()->get();
        //------------------------------------
        if(Auth::check()) {
            $this->authorize('view',$id);
            $test = DB::table('teams')
                ->join('users', 'teams.id', '=', 'users.team_id')

                ->select('first_name', 'last_name')
                ->where('role_id', '=', Role::team_leader)
                ->where('team_id', '=', $id->id)
                ->get();

            $test1 = DB::table('teams')
                ->join('users', 'teams.id', '=', 'users.team_id')
                ->select('first_name', 'last_name')
                ->where('role_id', '=', Role::team_member)
                ->where('team_id', '=', $id->id)
                ->get();
            return response()->json(['team' => $id, 'leader' => $test, 'members' => $test1],Response::HTTP_OK);
        }





    }


    public function destroy(Team $id)
    {
        if(Auth::check())
        {
            $this->authorize('delete',Team::class);
            $id->delete();
            return response()->json(['message'=>'deleted']);
        }


    }
}
