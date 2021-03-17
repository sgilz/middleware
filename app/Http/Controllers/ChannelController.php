<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Channel;
use App\Http\Resources\ChannelResource;

class ChannelController extends Controller
{
    public function create(Request $request)
    {
        //checks field correctness
        $validator = Channel::validate($request);
        if ($validator->fails()) {
            return response()->json([
                "message" => "Invalid data",
                "errors" => $validator->errors()->all(),
            ], 400);
        }
        $validatedData = $validator->validated();

        //checks if the name is already assigned to another queue
        if (Channel::where("name", $validatedData["name"])->exists()) {
            return response()->json([
                "message" => "Invalid data",
                "errors" => ["The name has already been taken"],
            ], 409);
        }

        //creates the new queue and saves it to DB
        $channel = Channel::create([
            "name" => $validatedData["name"],
            "user_id" => $request->user()->getId(),
        ]);
        return response()->json([
            "message" => "Channel created successfully",
            "data" => $channel,
        ], 201);
    }

    public function delete(Request $request)
    {
        //checks field correctness
        if (!$request->filled("name")) {
            return response()->json([
                "message" => "Invalid data",
                "errors" => ["Channel name not provided"],
            ], 400);
        }

        $channel = Channel::where("name", $request["name"])->first();
        if ($channel) {
            $channel->delete();
            return response()->json([
                "message" => "Channel deleted successfully",
            ]);
        } else {
            return response()->json([
                "message" => "Not found",
                "errors" => ["There is not channel with this name"],
            ], 404);
        }
    }

    public function list()
    {
        return response()->json([
            ChannelResource::collection(Channel::all()),
        ]);
    }

    public function suscribe(Request $request)
    {
        //checks field correctness
        if (!$request->filled("name")) {
            return response()->json([
                "message" => "Invalid data",
                "errors" => ["Channel name not provided"],
            ], 400);
        }

        $channel = Channel::where("name", $request["name"])->first();

        if($channel){
           
        }
    }
}
