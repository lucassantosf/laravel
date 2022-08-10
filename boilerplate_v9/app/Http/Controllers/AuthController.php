<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use DB;
use Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Zenvia\Notifications\SendSMS; 

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function login()
    {
        $credentials = request(['email', 'password']);

        if (!auth()->attempt($credentials))
            return response()->json(['error' => 'Unauthorized'], 401);

        return response()->json([
            'user' => auth()->user(),
            'access_token' => auth()->user()->createToken('')->accessToken,
            // 'role' => auth()->user()->roles->pluck('name'), 
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            'token_type' => 'bearer',
        ]);
    }

    public function me()
    {
        return response()->json(auth()->user());
    }

    public function logout(Request $request)
    {
        auth()->user()->token()->revoke();

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function register(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => $request->get('password'),
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json(compact('user','token'),201);
    }

}