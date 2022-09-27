<?php

namespace App\Http\Controllers;

use App\Role;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Laravel\Sanctum\PersonalAccessToken;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{

    protected $token = '';

    public function register(Request $request)
    {
        $roleID = Role::where('title', $request->input('role_id'))->value('id');
        $isAdmin = $roleID == 1;
        $isCenter = $roleID == 2;
        $isPilot = $roleID == 3;

        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|unique:users|email',
            'role_id' => 'required',
            'gender' => 'required',
            'national_id' => 'required|unique:users',
            'password' => 'required',
        ]);

        return User::create([
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
            'gender' => $request->input('gender'),
            'national_id' => $request->input('national_id'),
            'password' => Hash::make($request->input('password')),
            'isAdmin' => $isAdmin,
            'isCenter' => $isCenter,
            'isPilot' => $isPilot,
        ]);
    }

    public function login(Request $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response([
                'message' => 'Invalid credentials!'
            ], Response::HTTP_UNAUTHORIZED);
        }

        $user = Auth::user();

        $token = $user->createToken('token')->plainTextToken;

        $cookie = cookie('jwt', $token, 60 * 24);

        return response()->json([
            'user' => Auth::user(),
            'token' => $token
        ]);
        //->withCookie($cookie)
    }

    public function logout()
    {
        // $cookie = Cookie::forget('jwt');

        // // return Auth::user();
        // $user = Auth::user();
        // Auth::logout($user);
        $token = '';

        // return response()->json([
        //     'message' => 'Logout successfully'
        // ]);
        return response()->json([
            // 'user' => Auth::user(),
            'token' => $token
        ]);
    }

    public function user()
    {
        return Auth::user();
    }

    // public function login(Request $request)
    // {
    //     if (!Auth::attempt($request->only('email', 'password'))) {
    //         return response([
    //             'message' => 'Invalid credentials!'
    //         ], Response::HTTP_UNAUTHORIZED);
    //     }

    //     $user = Auth::user();

    //     $accessToken = $user->createToken('access_token')->plainTextToken;
    //     $refreshToken = $user->createToken('refresh_token')->plainTextToken;

    //     $cookie = cookie('refreshToken', $refreshToken, 60 * 24 * 7); // 1 week

    //     return response([
    //         'accessToken' => $accessToken,
    //         'refreshToken' => $refreshToken
    //     ])->withCookie($cookie);
    // }

    // public function user()
    // {
    //     return Auth::user();
    // }

    // public function refresh(Request $request)
    // {
    //     $refreshToken = $request->cookie('refreshToken');

    //     $token = PersonalAccessToken::findToken($refreshToken);

    //     $user = $token->tokenable;

    //     $accessToken = $user->createToken('access_token')->plainTextToken;

    //     return response([
    //         'token' => $accessToken
    //     ]);
    // }

    // public function logout()
    // {
    //     $cookie = Cookie::forget('refreshToken');

    //     return response([
    //         'message' => 'Success'
    //     ])->withCookie($cookie);
    // }
}
