<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Channel;
use App\Models\User;
use App\Models\ChannelsUsers;
use App\Http\Resources\ChannelResource;
use App\Models\Message;
use App\Http\Resources\MessageResource;

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

    public function subscribe(Request $request)
    {
        if (!$request->filled("name")) {
            return response()->json([
                "message" => "Invalid data",
                "errors" => ["Channel name not provided"],
            ], 400);
        }

        $channel = Channel::where("name", $request["name"])->first();
        $user = $request->user();

        if ($channel) {
            $validator = User::validateSuscription($request);
            if ($validator->fails()) {
                return response()->json([
                    "message" => "Invalid data",
                    "errors" => $validator->errors()->all(),
                ], 400);
            }

            $suscribed = ChannelsUsers::where("user_id", $user->getId())
                ->where("channel_id", $channel->getId())
                ->exists();

            if ($suscribed) {
                return response()->json([
                    "message" => "You have already subscribed to " . $request["name"],
                ], 400);
            } else {

                $user->setIp($request["ip"]);
                $user->setPort($request["port"]);
                $user->save();

                $channelsUsers = ChannelsUsers::create(
                    [
                        "channel_id" => $channel->getId(),
                        "user_id" => $user->getId(),
                    ]
                );

                return response()->json([
                    "message" => "Subscription to " . $request["name"] . " successfully",
                    "data" => $channelsUsers,
                ], 201);
            }
        }
    }

    public function push(Request $request)
    {
        //checks field correctness
        if (!($request->filled("channel") &&
            $request->filled("body"))) {
            return response()->json([
                "message" => "Invalid data",
                "errors" => ["Fields 'channel' and 'body' are mandatory"],
            ], 400);
        }

        //checks if the queue exists
        $channel = Channel::where("name", $request["channel"])->first();
        echo $channel->getId();
        if ($channel) {
            //creates a new message and saves it to DB
            Message::create([
                "body" => $request["body"],
                "date" => date('Y-m-d H:i:s'),
                "sent" => false,
                "channel_id" => $channel->getId(),
            ]);
            return response()->json([
                "message" => "Message pushed to " . $channel->getName() . " successfully",
            ], 201);
        } else {
            return response()->json([
                "message" => "Not found",
                "errors" => ["There is not channel with this name"],
            ], 404);
        }
    }

    public function getMessages(Request $request)
    {
        $user = $request->user();
        $channelsSuscribed = ChannelsUsers::where("user_id", $user->getId())->get();
        $channelMessages = array();
        foreach ($channelsSuscribed as $chs) {
            $messages = Message::where("channel_id", $chs->getChannelId())->get();
            $channel = Channel::where("id",  $chs->getChannelId())->first();
            if (!$messages->isEmpty()) {
                $channelMessages[$channel->getName()] = $messages;
            }
            else{
                $channelMessages[$channel->getName()] = "This channel has not messages yet";
            }
        }
        return response()->json([
            "messages" => $channelMessages
        ]);
    }
}
