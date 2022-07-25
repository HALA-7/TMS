<?php

namespace App\Http\Controllers;

use App\Http\Requests\EventRequest;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class EventController extends Controller
{

    public function store(EventRequest $request)
    {
        $this->authorize('create',Event::Class);
        $event=Event::query()->create([

            'event_name'=>$request->event_name,
        'start_date'=>$request->start_date,
        'end_date'=>$request->end_date,
        'user_id'=>Auth::id()
            ]);
        return response()->json(['added done'=> $event],201);
    }

    public  function update(EventRequest $request,Event $id)
    {
        $this->authorize('update',$id);
        $id->update([
            'event_name'=>$request->event_name,
            'start_date'=>$request->start_date,
            'end_date'=>$request->end_date,
            'user_id'=>Auth::id()
        ]);

        return response()->json(['after update'=> $id], \Illuminate\Http\Response::HTTP_OK);

    }

    public  function  destroy(Event $id)
    {
        $this->authorize('delete',$id);
        $id->delete();
        return \response()->json(['done'],\Illuminate\Http\Response::HTTP_OK);
    }

    public function MyEvents()
    {
       $ans=DB::table('events')->where('user_id','=',Auth::id())->get();
        return \response()->json(['all events'=>$ans],\Illuminate\Http\Response::HTTP_OK);

    }

    public function show(Event $id)
    {
        $this->authorize('view',$id);
        return \response()->json(['the event'=>$id]);

    }






}
