<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;

class AuthenticationController extends Controller
{
    //

    public function register(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users',
            'name' => 'required|string',
            'password' => 'required'
        ]);

        if($validator->fails()){
            return response()->json([
                'errors' => true,
                'message' => $validator->errors()
            ]);
        }

        $data = $request->all();
        $password = $data['password'];
        $data['password'] = Hash::make($password);
        if($request->isAdmin){
            $data['isAdmin'] = 1;
        }
        $user = User::create($data);

        return response()->json([
            'status' => 200,
            'message' => "Admin added",
            'data' => $user
        ], 201);
    }

    public function login(Request $request){
        $user = null;
        $data = $request->all();
        $token = auth()->attempt($data);;
        if (!$token) {
            throw new AuthenticationException("Incorrect credentials");
        }
        $user = User::where('id', auth()->user()->id)->firstOrFail();
        $user->access_token = $token;
        return response()->json([
            'status' => 200,
            'message' => "Login successful",
            'data' => $user
        ], 201);
    }
}
