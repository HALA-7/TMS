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

    public function destroy(Team $id)
    {
        if(Auth::check())
        {
            $this->authorize('delete',Team::class);
            $id->delete();
            return response()->json(['message'=>'deleted']);
        }


    }


    // TO SHOW ALL TEAMS ONLY ITS NAME
    public function ShowTeams()
    {
        if(Auth::check())
        {
            $this->authorize('viewAny',Team::class);
            $all_team=Team::query()->get();
            return response()->json($all_team);
        }
    }


    //TO SHOW EACH TEAM AND ITS DETAILS (the leaders and members)
    public function ShowTeam(Team $id)
    {

        if(Auth::check()) {
            $this->authorize('view',$id);

            $leader = DB::table('teams')
                ->join('users', 'teams.id', '=', 'users.team_id')
                ->join('leaders','users.id','=','leaders.user_id')
                ->select('role_id','users.id','first_name', 'last_name','img_profile')
                ->where('role_id', '=', Role::team_leader)
                ->where('team_id', '=', $id->id)
                ->get();

            //$id->leader()->get();
            //$id->members()->get();
            $members = DB::table('teams')
                ->join('users', 'teams.id', '=', 'users.team_id')
                ->join('members','users.id','=','members.user_id')
                ->select('role_id','users.id','first_name', 'last_name','img_profile')
                ->where('role_id', '=', Role::team_member)
                ->where('team_id', '=', $id->id)
                ->get();
            return response()->json([ 'leader' => $leader, 'members' => $members],Response::HTTP_OK);
        }

    }






}
