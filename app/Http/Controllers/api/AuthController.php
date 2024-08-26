<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request){
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ]);

        $user  = User::where('email' , $credentials['email'])->first();
        if(!$user || !Hash::check($credentials['password'], $user->password)){
            return response()->json([
                "message" => "error in email or password"
            ], 401);
        }

        if ($user->is_block) {
            return response()->json([
                "message" => 'Your account is blocked'
            ], 403);
        }

        $token = $user->createToken($user->name . 'authToken')->plainTextToken;
        return response()->json([
            "token" => $token
        ], 200);
    }


    public function register(Request $request){
        $validateData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'image' =>  'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $imageName = " ";
        if($request->hasFile('image')){
            $image = $request->file("image");
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $imageName);
        }

        User::create([
            'name' => $validateData['name'],
            'email' => $validateData['email'],
            'password' => Hash::make($validateData['password']),
            "image" => $imageName,
        ]);

        return response()->json([
            "message" => "User created compeleted"
        ], 200);

    }

    public function logout(Request $request){
        $request->user()->tokens()->delete();
        return response()->json([
            "message" => "logout compeleted"
        ], 200);
    }
}
