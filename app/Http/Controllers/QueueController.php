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
        $validator = Queue::validate($request);
        if($validator->fails()){
            return response()->json([
                "message" => "Invalid data",
                "errors" => $validator->errors()->all(),
            ],400);
        }

        $validatedData = $validator->validated();
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

    }
}
