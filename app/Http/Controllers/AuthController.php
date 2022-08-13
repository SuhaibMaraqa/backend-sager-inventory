<?php

namespace App\Http\Controllers;

use App\Role;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{

    public function register(Request $request)
    {
        $roleID = Role::where('title', $request->input('role_id'))->value('id');

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
            'role_id' => $roleID,
            'gender' => $request->input('gender'),
            'national_id' => $request->input('national_id'),
            'password' => Hash::make($request->input('password')),
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

        $cookie = cookie('jwt', $token, 60);

        return response([
            'message' => 'Success'
        ])->withCookie($cookie);
    }

    public function logout()
    {
        $cookie = Cookie::forget('jwt');

        return response([
            'message' => 'Logout successfully'
        ])->withCookie($cookie);
    }

    public function user()
    {
        return Auth::user();
    }

    // public function __construct()
    // {
    //     return $this->middleware('auth:api')->only('me');
    // }

    // public function index()
    // {
    //     return 'Hello';
    // }

    // public function register(Request $request)
    // {
    //     $roleID = Role::where('title', $request['role_id'])->value('id');
    //     $request['role_id'] = $roleID;

    //     $request->validate([
    // 'first_name' => 'required',
    // 'last_name' => 'required',
    // 'role_id' => 'required',
    // 'email' => 'required|email',
    // 'password' => 'required|min:6',
    // 'gender' => 'required',
    // 'national_id' => 'required'
    //     ]);

    //     $data = $request->only('first_name', 'last_name', 'role_id', 'email', 'password', 'gender', 'national_id');

    //     $user = new User($data);
    //     $user->save();

    //     return response()->json([
    //         'message' => 'User registered successfully'
    //     ]);
    // }

    // public function login(Request $request)
    // {
    //     $request->validate([
    //         'email' => 'required',
    //         'password' => 'required',
    //     ]);

    //     $credentials = $request->only(['email', 'password']);

    //     $token = auth()->attempt($credentials);

    //     if (!$token) {
    //         return response()->json(['message' => 'Invalid email or password'], 403);
    //     }

    //     return response()->json([
    //         'message' => 'Logged in successfully',
    //         'token' => $token
    //     ]);
    // }

    // public function me(Request $request)
    // {
    //     return $request->user();
    //     return ([
    //         'email' => $request->email,
    //         'firstName' => $request->first_name
    //     ]);
    // }
}
