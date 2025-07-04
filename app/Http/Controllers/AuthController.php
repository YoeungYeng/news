<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function login(Request $request)
{
    try {
        // Validate login credentials
        $validation = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ]);

        if ($validation->fails()) {
            return response()->json([
                'status' => 400,
                'message' => "Bad request",
                'errors' => $validation->errors()
            ], 400);
        }

        // Attempt to log in using JWT
        if (!$token = JWTAuth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'status' => 401,
                'message' => 'Invalid credentials'
            ], 401);
        }

        // Get authenticated user
        $user = JWTAuth::user();

        // Optional: restrict login to admin only
        if ($user->role !== 'admin') {
            return response()->json([
                'status' => 403,
                'message' => 'Access denied. Admins only.'
            ], 403);
        }

        // Success
        return response()->json([
            'status' => 200,
            'message' => 'Login successful',
            'token' => $token,
            
        ]);

    } catch (JWTException $e) {
        return response()->json([
            'status' => 500,
            'error' => 'Could not create token'
        ], 500);

    } catch (Exception $e) {
        return response()->json([
            'status' => 500,
            'error' => 'Something went wrong!'
        ], 500);
    }
}
}
