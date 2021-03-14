<?php

namespace App\Http\Controllers;

use App\Models\Queue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            "user_id" => Auth::id(),
        ]);
        return response()->json([
            "message" => "queue '{$queue->getName()}' created successfully",
            "data" => $queue,
        ]);
    }

    public function delete(Request $request)
    {

    }
}
