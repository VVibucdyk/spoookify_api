<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        // cek apakah user sudah ada
        // dd($request->all());
        $getUser = User::where('email', $request->email)->orWhere('username', $request->username)->get();
        if(count($getUser) > 0) {
            echo json_encode([
                'success' => false,
                'message' => 'Gagal Signup! Email/Username sudah terdaftar!'
            ]);
        }else{
            //create user
            $user = User::create([
                'name'      => $request->name,
                'username'  => $request->username,
                'email'     => $request->email,
                'password'  => Hash::make($request->password)
            ]);

            if($user) {
                echo json_encode([
                    'success' => true,
                    'user'    => $user,
                    'message' => "Berhasil signup"
                ]);
            }
        }
    }
}
