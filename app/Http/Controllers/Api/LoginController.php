<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        
        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();
            if ($user->mechanicInfo) {
                $role_id = $user->mechanicInfo->id;
            } else {
                $role_id = $user->customerInfo->id;
                return response()->json(['message' => 'Unauthorized'], 401);
            }
            return response()->json([
                'token' => $user->createToken('GenialSystem')->plainTextToken,
                'user' => $user,
                'role_id' => $role_id
            ]);
        }

        return response()->json(['message' => 'Unauthorized'], 401);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out']);
    }
}
