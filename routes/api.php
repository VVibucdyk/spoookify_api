<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('signup', App\Http\Controllers\Api\RegisterController::class);
Route::get('login', App\Http\Controllers\Api\LoginController::class);
Route::post('set-topic-user', [UserController::class, 'setTopicUser']);

Route::get('get-all-topics', [TopicController::class, 'index']);

Route::post('create-post', [PostController::class, 'store']);

Route::get('newest-post', [PostController::class, 'newestPost']);
Route::get('see-post', [PostController::class, 'show']);
Route::get('post-like', [PostController::class, 'toggleLike']);
Route::get('post-bookmark', [PostController::class, 'toggleBookmark']);


Route::get('test', function(){
    echo "Hello world";
});