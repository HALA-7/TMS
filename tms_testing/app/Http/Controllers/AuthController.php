<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Validation\Rule;




class AuthController extends Controller
{


    public function login(Request $request)
    {

        $request->validate([
            'employee_identical'=>['required','min:3','max:8'],
            'password' => ['required','min:6','string'],
            'remember_token'=>['boolean']
        ]);


        $temp=$request->only(['employee_identical','password']);

        if(!Auth::attempt($temp))
        {
            return \response()->json(['message'=>'Unauthorized'],401);
        }

        $user=Auth::user();
        $tokenResult=$user->createToken('personal access token');
        $data['user'] = $user; //store the info about the user that login to application
        $data['typeToken'] = 'Bearer'; //this is the type of the token
        $data['token'] = $tokenResult->accessToken;
      //  return \response()->json(['message:'=>'login done','the user:'=>$data]);
        return \response()->json($data,Response::HTTP_OK);
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json(['message'=>'successfully logged out']);
    }




}
