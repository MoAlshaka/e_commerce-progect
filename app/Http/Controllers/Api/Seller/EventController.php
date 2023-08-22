<?php

namespace App\Http\Controllers\Api\Seller;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventResource;
use App\Models\Event;
use App\Models\Seller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EventController extends Controller
{

    public function index(Request $request){
        $access_token = $request->bearerToken();
        $seller=Seller::where('access_token',$access_token)->first();
        $Events= Event::where('seller_id',$seller->id)->get();
        return response()->json(EventResource::collection($Events));

    }

    public function show(Request $request,$id){
        $access_token = $request->bearerToken();
        $seller=Seller::where('access_token',$access_token)->first();
        $event= Event::find($id);
        if(!$event){
            return response()->json(['message' => 'Event not found'], 404);
        }
        if ($seller->id == $event->seller_id ) {
            $event= new EventResource($event);
            return response()->json($event);
        }else {
            return response()->json(['message' => 'You do not have access to show this Event'], 403);
        }
    }

    public function store(Request $request){
        $access_token = $request->bearerToken();
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'desc' => 'required',
            'original_price' => 'required',
            'price_after_discount' => 'required',
            'stock' => 'required|integer',
            'tag' => 'required',
            'cate_id' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'image' => 'required|image|mimes:png,jpg,jpeg',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response($errors, 400);
        }

        $image=$request->image;
        $ext=$image->getClientOriginalExtension();
        $new_name=uniqid() . '.' . $ext;
        $image->move(public_path('images/events'),$new_name);
        $seller=Seller::where('access_token',$access_token)->first();
        $event=Event::create([
            'name'=>$request->name,
            'desc'=>$request->desc,
            'image'=>$new_name,
            'original_price'=>$request->original_price,
            'price_after_discount'=>$request->price_after_discount,
            'stock'=>$request->stock,
            'tag'=>$request->tag,
            'cate_id'=>$request->cate_id,
            'start_date'=>$request->start_date,
            'end_date'=>$request->end_date,
            'seller_id'=>$seller->id,
        ]);
        $data=[
            'id'=>$event->id,
            'name'=>$event->name,
            'description'=>$event->desc,
            'original_price'=>$event->original_price,
            'price_after_discount'=>$event->price_after_discount,
            'cate_id'=>$event->cate_id,
            'tag'=>$event->tag,
            'stock'=>$event->stock,
            'start_date'=>$event->start_date,
            'end_date'=>$event->end_date,
            'image'=>"public/images/events/$event->image",
            'author' => $event->seller->name,
            'created_at'=>$event->created_at,
            'updated_at'=>$event->updated_at,
        ];

        return response()->json($data,201);
    }

    public function update(Request $request,$id){
        $access_token = $request->bearerToken();
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'desc' => 'required',
            'original_price' => 'required',
            'price_after_discount' => 'required',
            'stock' => 'required|integer',
            'tag' => 'required',
            'cate_id' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'image' => 'nullable|image|mimes:png,jpg,jpeg',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }
        $seller = Seller::where('access_token', $access_token)->first();
        $event = Event::find($id);
        if (!$event) {
            return response()->json(['message' => 'Event not found'], 404);
        }
        if ($seller->id == $event->seller_id) {
            $image_name = $event->image;
            if ($request->hasFile('image')) {
                unlink(public_path("images/events/$image_name"));
                $image = $request->file('image');
                $ext = $image->getClientOriginalExtension();
                $image_name = uniqid() . '.' . $ext;
                $image->move(public_path('images/events'), $image_name);
            }
            $event->update([
                'name' => $request->name,
                'desc' => $request->desc,
                'image' => $image_name,
                'original_price' => $request->original_price,
                'price_after_discount' => $request->price_after_discount,
                'stock' => $request->stock,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'tag' => $request->tag,
                'cate_id' => $request->cate_id,
            ]);
            $updatedEvent = Event::find($id);
            $data = [
                'id' => $updatedEvent->id,
                'name' => $updatedEvent->name,
                'description' => $updatedEvent->desc,
                'original_price' => $updatedEvent->original_price,
                'price_after_discount' => $updatedEvent->price_after_discount,
                'cate_id' => $updatedEvent->cate_id,
                'tag' => $updatedEvent->tag,
                'stock' => $updatedEvent->stock,
                'start_date' => $updatedEvent->start_date,
                'end_date' => $updatedEvent->end_date,
                'image' => "public/images/events/$updatedEvent->image",
                'author' => $updatedEvent->seller->name,
                'created_at' => $updatedEvent->created_at,
                'updated_at' => $updatedEvent->updated_at,
            ];
            return response()->json($data);
        } else {
            return response()->json(['message' => 'You do not have access to update this Event'], 403);
        }
    }

    public function delete(Request $request,$id){
        $access_token = $request->bearerToken();
        $seller = Seller::where('access_token', $access_token)->first();
        if (!$seller) {
            return response()->json(['message' => 'Seller not found'], 404);
        }
        $event = Event::find($id);
        if (!$event) {
            return response()->json(['message' => 'Event not found'], 404);
        }
        if ($seller->id == $event->seller_id) {
            $imagePath = public_path("images/events/{$event->image}");

            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
            $event->delete();
            return response()->json(['message' => 'Event deleted successfully']);
        } else {
            return response()->json(['message' => 'You do not have access to delete this Event'], 403);
        }
    }


}
