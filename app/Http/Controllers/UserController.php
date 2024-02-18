<?php

namespace App\Http\Controllers;

use App\Models\Topic;
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

    function getProfile(Request $request) {
        $data = User::where('id', $request->user_id)->first();
        $topic = Topic::get();
        return response()->json([
            'data' => $data,
            'topic' => $topic
        ], 200);
    }

    function editProfileProcess(Request $request) {
        $user = User::where('id', $request->user_id)->first();
        if($user !== null) {
            if($user->profile_path !== $request->img) {
                $filename = time().'-'.$request->user_id. '.' . $request->img->extension();
                $request->img->move(public_path('img/pp'), $filename);
            }else{
                $filename = $request->img;
            }
            User::where('id', $request->user_id)->update([
                'topic_id' => $request->topic_id,
                'about_me' => $request->about_me,
                'name' => $request->name,
                'profile_path' => $filename
            ]);

            return response()->json([
                'status' => true,
            ], 200);
        }
    }
}
