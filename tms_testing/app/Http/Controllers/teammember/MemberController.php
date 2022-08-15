<?php

namespace App\Http\Controllers\teammember;

use App\Http\Controllers\Controller;

use App\Http\Requests\teammember\member\CreateMemberRequest;
use App\Http\Requests\teammember\member\UpdateMemberRequest;
use App\Models\Leader;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MemberController extends Controller
{



    public function store(CreateMemberRequest $request)
    {    $gg= DB::table('members')->where('user_id','=',Auth::id())->value('user_id');
        $image_temp=null;

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

            $data = Member::query()->create([
                'img_profile' => $image_temp,
                'phone' => $request->phone,
                'contact' => $request->contact,
                'education' => $request->education,
                'user_id' => Auth::id(),
            ]);
            return response()->json(['message' => 'information added successfully ', 'the info:' => $data], 201);
        }
    }







    public function update(UpdateMemberRequest $request,Member $member)
    {
        $this->authorize('update',$member);
        $image_temp=null;
        if($request->hasFile('img_profile')) {
            $file = $request->file('img_profile');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move('images',$filename);
            $link=asset('images/'.$filename);
            $link2=substr($link,-21);
            $image_temp = $link2;
        }

        $member->update([
            'img_profile'=>$image_temp,//'mimes:jpg,png,jpeg'
            'phone'=>$request->phone,
            'contact'=>$request->contact,
            'education'=>$request->education,
            'user_id'=>Auth::id(),
        ]);
        return response()->json(['message'=>'info updated successfully','the info:'=>$member],Response::HTTP_OK);
    }



}
