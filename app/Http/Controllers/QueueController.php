<?php

namespace App\Http\Controllers;

use App\Models\Queue;
use Illuminate\Http\Request;

class QueueController extends Controller
{
    public function push(Request $request)
    {
        
    }

    public function pull(Request $request)
    {

    }

    public function create(Request $request)
    {
        //checks field correctness
        $validator = Queue::validate($request);
        if($validator->fails()){
            return response()->json([
                "message" => "Invalid data",
                "errors" => $validator->errors()->all(),
            ],400);
        }
        $validatedData = $validator->validated();

        //checks if the name is already assigned to another queue
        if(Queue::where("name",$validatedData["name"])->exists()){
            return response()->json([
                "message" => "Invalid data",
                "errors" => ["The name has already been taken"],
            ],409);
        }

        //creates the new queue and saves it to DB
        $queue = Queue::create([
            "name" => $validatedData["name"],
            "user_id" => $request->user()->getId(),
        ]);
        return response()->json([
            "message" => "Queue created successfully",
            "data" => $queue,
        ],201);
    }

    public function delete(Request $request)
    {
        //checks field correctness
        $validator = Queue::validate($request);
        if($validator->fails()){
            return response()->json([
                "message" => "Invalid data",
                "errors" => $validator->errors()->all(),
            ],400);
        }

        $validatedData = $validator->validated();
        $queue = Queue::where("name",$validatedData["name"]);
        if($queue->exists()){
            $queue->delete();
            return response()->json([
                "message" => "Queue deleted successfully",
            ]);
        }else{
            return response()->json([
                "message" => "Not found",
                "errors" => ["There is not queue with this name"],
            ],404);
        }
    }
}
