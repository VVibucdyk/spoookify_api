<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
         //get credentials from request
         $credentials = $request->only('email', 'password');

         $get_user = User::where('email', $request->email)->get();
         
         if(count($get_user) > 0) {
            if(Auth::attempt($credentials)){
                //if auth success
                return response()->json([
                    'success' => true,
                    'user'    => $get_user[0],
                    'message' => "Berhasil Login!"
                ], 200);
            }

            return response()->json([
                'success' => false,
                'message' => 'Email atau Password Anda salah'
            ], 200);
            
        }else{
            return response()->json([
                'success' => false,
                'message' => "User tidak terdaftar"
            ], 200);
        }
    }
}
