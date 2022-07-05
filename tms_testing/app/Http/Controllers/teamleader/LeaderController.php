<?php

namespace App\Http\Controllers\teamleader;

use App\Http\Controllers\Controller;
use App\Http\Requests\admin\user\CreateUserRequest;
use App\Http\Requests\teamleader\leader\CreateLeaderRequest;
use App\Http\Requests\teamleader\leader\UpdateLeaderRequest;
use App\Models\Leader;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LeaderController extends Controller
{
   // to show all profile with all information
    public function index()
    {
        $all_leader=Leader::with('user')->get();
      return  response()->json($all_leader);
    }


    public function store(CreateLeaderRequest  $request)
    {
         $image_temp=null;
        $gg= DB::table('leaders')->where('user_id','=',Auth::id())->value('user_id');
       // dd($gg)
       // dd(Auth::id());
        if($gg==Auth::id())
        {
            return response()->json(['message' => 'You Are Previously create,instead edit your profile']);
        }
        else {
            if ($request->hasFile('img_profile')) {
                $file = $request->file('img_profile');
                $extension = $file->getClientOriginalExtension();
                $filename = time() . '.' . $extension;
                $file->move('public/image', $filename); //it will store the image in public folder
                $image_temp = $filename;
            }

            $data = Leader::query()->create([
                'img_profile' => $image_temp,
                'phone' => $request->phone,
                'contact' => $request->contact,
                'education' => $request->education,
                'experience' => $request->experience,
                'user_id' => Auth::id(),
            ]);
            //  dd(Auth::user()->first_name);
            return response()->json(['message' => 'information Added successfully', 'the info:' => $data], 201);
        }
    }

    //to specific profile with all information
    public function show(Leader $id)
    {
      //  dd($s);
      return response()->json(['your info:'=>$id,$id->user()->get()]);
    }


    public function update(UpdateLeaderRequest $request,Leader $leader)
    {
        $image_temp='';

        if($request->hasFile('img_profile')) {
            $file = $request->file('img_profile');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move('public/image', $filename);
            $image_temp = $filename;
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

    public function destroy($id)
    {

    }
}
