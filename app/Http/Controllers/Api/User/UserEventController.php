<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventResource;
use App\Models\Event;
use Illuminate\Http\Request;

class UserEventController extends Controller
{
    //
    public function all_event(){
        $events=Event::all();
        if(!$events){
            return response()->json(['message' => ' No Events founded '], 404);
        }
        return response()->json(EventResource::collection($events));
    }

    public function one_event($id){
        $event=Event::find($id);
        if(!$event){
            return response()->json(['message' => 'Event not found'], 404);
        }
        return response()->json(new EventResource($event));

    }
}
