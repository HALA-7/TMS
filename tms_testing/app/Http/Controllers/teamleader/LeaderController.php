<?php

namespace App\Http\Controllers\teamleader;

use App\Http\Controllers\Controller;
use App\Http\Requests\admin\user\CreateUserRequest;
use App\Http\Requests\teamleader\leader\CreateLeaderRequest;
use App\Http\Requests\teamleader\leader\UpdateLeaderRequest;
use App\Models\Leader;
use App\Models\Meeting;
use App\Models\Member;
use App\Models\Role;
use App\Models\User;
use App\Policies\LeaderPolicy;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class LeaderController extends Controller
{


    public function store(CreateLeaderRequest  $request)
    {
         $image_temp=null;
        $gg= DB::table('leaders')->where('user_id','=',Auth::id())->value('user_id');


        // IF THE LEADER HAVE A PREVIOUS INFORMATION
        if($gg==Auth::id())
        {
            return response()->json(['message' => 'You Are Previously create,instead edit your profile']);
        }

        else {
            if ($request->hasFile('img_profile')) {
                $file = $request->file('img_profile');
                $extension = $file->getClientOriginalExtension();
                $filename = time() . '.' . $extension;
                $file->move('images',$filename);
                $link=asset('images/'.$filename);
                $link2=substr($link,-21);
                $image_temp = $link2;
            }

            $data = Leader::query()->create([
                'img_profile' => $image_temp,
                'phone' => $request->phone,
                'contact' => $request->contact,
                'education' => $request->education,
                'experience' => $request->experience,
                'user_id' => Auth::id(),
            ]);

            return response()->json(['message' => 'information Added successfully', 'the info:' => $data], 201);
        }
    }





    public function update(UpdateLeaderRequest $request,Leader $leader)
    {
        $this->authorize('update',$leader);
        $image_temp=null;
        if($request->hasFile('img_profile'))
        {
            $file = $request->file('img_profile');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move('images',$filename);
            $link=asset('images/'.$filename);
            $link2=substr($link,-21);
            $image_temp = $link2;

        }


        $leader->update([
            'img_profile'=>$image_temp,//'mimes:jpg,png,jpeg'
            'phone'=>$request->phone,
            'contact'=>$request->contact,
            'education'=>$request->education,
            'experience'=>$request->experience,
            'user_id'=>Auth::id(),
        ]);
        return response()->json(['message'=>'information updated successfully','the info:'=>$leader],Response::HTTP_OK);

    }








}
