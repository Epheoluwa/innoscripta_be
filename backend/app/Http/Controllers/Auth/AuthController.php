<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\ResponseController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;

class AuthController extends ResponseController
{
    public function Register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return $this->sendErrorResponse('Validation failed.', $validator->errors()->first(), 422);
        }

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $userDetails['token'] = $user->createToken('authToken')->plainTextToken;
            $userDetails['user'] = $user;

            return $this->sendSuccessResponse($userDetails,'Account Created Successfully', 201);

        } catch (\Throwable $e) {
            return new JsonResponse([
                'status' => 500,
                'message' => 'Error:' . $e->getMessage(),
            ], 500);
        }
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendErrorResponse('Validation failed.', $validator->errors()->first(), 422);
        }

        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        try {
            Auth::attempt($credentials);
            $user = Auth::user();

           $userDetails['token'] = $user->createToken('authToken')->plainTextToken;
           $userDetails['user'] = $user;

            return $this->sendSuccessResponse($userDetails,'Logged in Successfully', 201);

        } catch (\Throwable $e) {

            return $this->sendErrorResponse('Incorrect Email Or Password.', $e->getMessage(), 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            // auth()->user()->currentAccessToken()->delete();
            Auth::guard('web')->logout();
            $user = $request->user();
            $user->tokens()->delete();
            // Auth::logout();
        
        
        return $this->sendSuccessResponse("success",'Logged out Successfully', 201);
        } catch (\Throwable $e) {
            return $this->sendErrorResponse('Cannot Logout now try again', $e->getMessage(), 500);
        }
        
    }
}
