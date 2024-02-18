<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //

        $filename = time().'-'.$request->arrUser. '.' . $request->img->extension();
        $request->img->move(public_path('img/post'), $filename);
        

        $insert = Post::create([
            'user_id' => $request->arrUser,
            'topic_id' => $request->topics,
            'title_post' => $request->title,
            'thumbnail_path' => $filename,
            'content' => $request->text,
        ]);

        if($insert) {
            return response()->json([
               'status' => true
            ], 200);
        }else{
            return response()->json([
               'status' => false
            ], 200);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {

        //  true kan is seen
        if($request->user_id !== null || $request->user_id !== ""){
            $activity_user = DB::table('history_activity_posts')->where('post_id', $request->id_post)->where('user_id', $request->user_id)->first();
            if($activity_user !== null) {
                DB::table('history_activity_posts')->where('user_id', $request->user_id)->where('post_id', $request->id_post)->update([
                    'is_seen' => true
                ]);
            }else{
                DB::table('history_activity_posts')->insert([
                    'user_id' => $request->user_id,
                    'post_id' => $request->id_post,
                    'is_seen' => true
                ]);
            }
        }

        // Get like komen and share
        $likes = DB::table('history_activity_posts')->where('is_like', true)->where('post_id', $request->id_post)->count();
        $seen = DB::table('history_activity_posts')->where('is_seen', true)->where('post_id', $request->id_post)->count();
        $save_bookmark = DB::table('history_activity_posts')->where('is_save_bookmark', true)->where('post_id', $request->id_post)->count();

        $topic = Topic::select('id', 'name_topic')->get();
        $data = Post::select('posts.*','posts.id as id','history_activity_posts.is_seen', 'history_activity_posts.is_like', 'history_activity_posts.is_save_bookmark', 'users.username')
        ->leftjoin('users', 'users.id', 'posts.user_id')
        ->leftjoin('history_activity_posts', 'history_activity_posts.post_id', 'posts.id')
        ->where('posts.id',$request->id_post)
        ->where('history_activity_posts.user_id',$request->user_id)->first();
        if($data !== null) {
            $arrTopic = explode(',', $data->topic_id);
            $arrTopicStore = [];
            foreach ($arrTopic as $key1 => $value1) {
                foreach ($topic as $key2 => $value2) {
                    if($value2->id == $value1) {
                        $arrTopicStore[] = $value2->name_topic;
                    }
                }
            }
            $data->arr_name_topic = $arrTopicStore;
            $data->likes = $likes;
            $data->seen = $seen;
            $data->save_bookmark = $save_bookmark;
            return response()->json([
                'status' => true,
                'data' => $data
            ], 200);
        }else{
            return response()->json([
                'status' => false,
            ], 200);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        $post = Post::where('id', $request->post_id)->first();
        if($post !== null) {
            if($post->thumbnail_path !== $request->img) {
                $filename = time().'-'.$request->arrUser. '.' . $request->img->extension();
                $request->img->move(public_path('img/post'), $filename);
            }else{
                $filename = $request->img;
            }

            $update = Post::where('id', $request->post_id)->update([
                'topic_id' => $request->topics,
                'title_post' => $request->title,
                'thumbnail_path' => $filename,
                'content' => $request->text,
            ]);

            if($update) {
                return response()->json([
                'status' => true
                ], 200);
            }else{
                return response()->json([
                'status' => false
                ], 200);
            }
        }else{
            return response()->json([
            'status' => false,
            'message' => 'Post ini tidak terdaftar pada database!'
            ], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post, Request $request)
    {
        // delete activity post
        $delete_post = Post::where('id', $request->post_id)->delete();
        if($delete_post) {
            $delete_activity = DB::table('history_activity_posts')->where('post_id', $request->post_id)->delete();
            return response()->json([
                'status' => true,
                'message' => "Berhasil Hapus!"
            ], 200);
        }else{
            return response()->json([
                'status' => true,
                'message' => "Gagal Hapus! Hubungi Admin"
            ], 200);
        }
    }

    public function newestPost(Request $request){
        $arrData = [];
        $topic = Topic::select('id', 'name_topic')->get();

        $get = Post::select('posts.*','posts.id as id', 'users.username')
        ->leftjoin('users', 'users.id', 'posts.user_id')
        // ->leftjoin('history_activity_posts', 'history_activity_posts.post_id', 'posts.id')
        // ->where('history_activity_posts.user_id',$request->user_id)
        ->limit(20)->get();

        foreach ($get as $key => $value) {
            $arrTopic = explode(',', $value->topic_id);
            
            $arrTopicStore = [];
            foreach ($arrTopic as $key1 => $value1) {
                foreach ($topic as $key2 => $value2) {
                    if($value2->id == $value1) {
                        $arrTopicStore[] = $value2->name_topic;
                    }
                }
            }
            $arrData[$key] = $value;
            $arrData[$key]->arr_name_topic = $arrTopicStore;
        }
        
        if($get) {
            return response()->json([
                'status' => true,
                'data' => $arrData
            ], 200);
        }else{
            return response()->json([
                'status' => false,
            ], 200);
        }
    }

    public function toggleLike(Request $request) {
        $user_like = DB::table('history_activity_posts')
        ->where('post_id', $request->post_id)
        ->where('user_id', $request->user_id)
        ->first();

        if($user_like == null) {
            $create = DB::table('history_activity_posts')
            ->insert([
                'is_like' => true,
                'post_id' => $request->post_id,
                'user_id' => $request->user_id
            ]);

            if($create) {
                return response()->json([
                    'status' => true
                ], 200);
            }

            return response()->json([
                'status' => false
            ], 200);
        }else{
            if($user_like->is_like == true) {
                $update = DB::table('history_activity_posts')
                ->where('post_id', $request->post_id)
                ->where('user_id', $request->user_id)
                ->update([
                    'is_like' => false
                ]); 
            }else{
                $update = DB::table('history_activity_posts')
                ->where('post_id', $request->post_id)
                ->where('user_id', $request->user_id)
                ->update([
                    'is_like' => true
                ]); 
            }

            if($update) {
                return response()->json([
                    'status' => true
                ], 200);
            }

            return response()->json([
                'status' => false
            ], 200);
        }
    }

    public function toggleBookmark(Request $request) {
        $user_like = DB::table('history_activity_posts')
        ->where('post_id', $request->post_id)
        ->where('user_id', $request->user_id)
        ->first();

        if($user_like == null) {
            $create = DB::table('history_activity_posts')
            ->insert([
                'is_save_bookmark' => true,
                'post_id' => $request->post_id,
                'user_id' => $request->user_id
            ]);

            if($create) {
                return response()->json([
                    'status' => true
                ], 200);
            }

            return response()->json([
                'status' => false
            ], 200);
        }else{
            if($user_like->is_save_bookmark == true) {
                $update = DB::table('history_activity_posts')
                ->where('post_id', $request->post_id)
                ->where('user_id', $request->user_id)
                ->update([
                    'is_save_bookmark' => false
                ]); 
            }else{
                $update = DB::table('history_activity_posts')
                ->where('post_id', $request->post_id)
                ->where('user_id', $request->user_id)
                ->update([
                    'is_save_bookmark' => true
                ]); 
            }

            if($update) {
                return response()->json([
                    'status' => true
                ], 200);
            }

            return response()->json([
                'status' => false
            ], 200);
        }
    }

    public function getEditPost(Request $request) {
        $topics = Topic::get();
        $data = Post::where('id', $request->post_id)->first();
        return response()->json([
            'topics' => $topics,
            'data' => $data
        ], 200);
    }
}
