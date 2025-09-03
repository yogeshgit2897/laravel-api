<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

use App\Models\User;

class AuthController extends Controller
{
    public function signup(Request $request)
    {
         $validateUser = Validator::make(
            $request->all(), 
            [
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required', 
            ]
        );

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation errors',
                    'errors' => $validateUser->errors()->all(),
                ], 422);
            }

            $user = User::create([
                   'name' => $request->name,
                   'email' => $request->email,
                   'password' => $request->password,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'User Created Successfully',
                'user' => $user,
            ], 201);

    }

    public function login(Request $request)
    {
        // yogesh@mail.com
        //pass 12345
        // 1|Yzm4yOSxOQlIq8JTNh0tQEPbdG5CO2NXf2bUmeLW8500922f //64 Character
         $validateUser = Validator::make(
            $request->all(), 
            [
                'email' => 'required|email',
                'password' => 'required',
            ]
        );
         if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Authentication Fails',
                    'errors' => $validateUser->errors()->all()
                ], 404);
            }
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password ])){
                $AuthUser = Auth::user();
                return response()->json([
                    'status' => true,
                    'message' => 'User Logged in Sucessfully',
                    'token' => $AuthUser->CreateToken("API Token")->plainTextToken,
                    'token_type' => 'bearer'
              ], 200);
                
        }else{
            return response()->json([
                'status' => false,
                'message' => 'Validation errors',
                'errors' => $validateUser->errors()->all(),
            ], 422);
        }    
        
    }

    public function logout(Request $request)
    {
        // return 'logout Fun';
           $user = $request->user();
           $user->tokens()->delete();
          
            return response()->json([
                    'status' => true,
                    'user' => $user,
                    'message' => 'You Logged Out Sucessfully'
              ], 200);
    }
}
