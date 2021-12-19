<?php

namespace App\Http\Controllers\GameProfile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Events\NewMessage;
use App\Models\Message;
use App\Models\User;

class ChatController extends Controller
{

    public function getMessagesFor($id)
    {
        Message::where('from', $id)->where('to', auth()->id())->update(['read' => true]);

        $messages = Message::where('order_id', $id)->with('user')->get();

        return response()->json($messages);
    }

    public function send(Request $request)
    {

        $message = Message::create([
            'order_id' => $request->order_id,
            'from' => auth()->id(),
            'to' => $request->contact_id,
            'text' => $request->text
        ]);

        broadcast(new NewMessage($message))->toOthers();

        return response()->json($message);
    }
}
