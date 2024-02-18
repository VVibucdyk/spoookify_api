<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    function setTopicUser(Request $request) {
        $user_id = $request->user_id;
        $topic = $request->topics;

        User::where('id', $user_id)->update([
            'topic_id' => $topic,
            'is_topic_already_choose' => true
        ]);

        return response()->json([
            'message' => "Berhasil set topic",
        ], 200);
    }
}
