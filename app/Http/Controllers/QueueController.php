<?php

namespace App\Http\Controllers;

use App\Http\Resources\MessageResource;
use App\Http\Resources\QueueResource;
use App\Models\Message;
use App\Models\Queue;
use Illuminate\Http\Request;

class QueueController extends Controller
{
    public function create(Request $request)
    {
        //checks field correctness
        $validator = Queue::validate($request);
        if ($validator->fails()) {
            return response()->json([
                "message" => "Invalid data",
                "errors" => $validator->errors()->all(),
            ], 400);
        }
        $validatedData = $validator->validated();

        //checks if the name is already assigned to another queue
        if (Queue::where("name", $validatedData["name"])->exists()) {
            return response()->json([
                "message" => "Invalid data",
                "errors" => ["The name has already been taken"],
            ], 409);
        }

        //creates the new queue and saves it to DB
        $queue = Queue::create([
            "name" => $validatedData["name"],
            "user_id" => $request->user()->getId(),
        ]);
        return response()->json([
            "message" => "Queue created successfully",
            "data" => $queue,
        ], 201);
    }

    public function delete(Request $request)
    {
        //checks field correctness
        if (!$request->filled("name")) {
            return response()->json([
                "message" => "Invalid data",
                "errors" => ["Queue name not provided"],
            ], 400);
        }

        $queue = Queue::where("name", $request["name"])
            ->where("user_id", $request->user()->getId())
            ->first();

        if ($queue) {
            $queue->delete();
            return response()->json([
                "message" => "Queue deleted successfully",
            ]);
        } else {
            return response()->json([
                "message" => "Not found",
                "errors" => ["There is not queue with this name in your ownership"],
            ], 404);
        }
    }

    public function list(Request $request)
    {
        return response()->json([
            QueueResource::collection(Queue::all()),
        ]);
    }

    public function push(Request $request)
    {
        //checks field correctness
        if (!($request->filled("queue") &&
            $request->filled("body"))) {
            return response()->json([
                "message" => "Invalid data",
                "errors" => ["Fields 'queue' and 'body' are mandatory"],
            ], 400);
        }

        //checks if the queue exists
        $queue = Queue::where("name", $request["queue"])->first();
        if ($queue) {
            //creates a new message and saves it to DB
            Message::create([
                "body" => $request["body"],
                "date" => date('Y-m-d H:i:s'),
                "sent" => false,
                "queue_id" => $queue->getId(),
            ]);
            return response()->json([
                "message" => "Message pushed to '{$queue->getName()}' successfully",
            ], 201);
        } else {
            return response()->json([
                "message" => "Not found",
                "errors" => ["There is not queue with this name"],
            ], 404);
        }

    }

    public function pull(Request $request)
    {
        //checks field correctness
        if (!$request->filled("queue")) {
            return response()->json([
                "message" => "Invalid data",
                "errors" => ["Queue name not provided"],
            ], 400);
        }

        //checks if the queue exists
        $queue = Queue::where("name", $request["queue"])->first();
        if ($queue) {
            //gets unsent messages from this queue
            $unsent_messages = Message::where("queue_id", $queue->getId())
                ->where("sent", false)
                ->get();

            //updates sent status for each message
            foreach ($unsent_messages as $msg) {
                $msg->setSent(true);
                $msg->save();
            }
            return response()->json([
                "messages" => MessageResource::collection($unsent_messages),
            ]);
        } else {
            return response()->json([
                "message" => "Not found",
                "errors" => ["There is not queue with this name"],
            ], 404);
        }
    }
}
