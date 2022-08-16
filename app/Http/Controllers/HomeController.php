<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Role;
use App\User;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function users(){
        return User::all();
    }

    public function index()
    {
        $roleId = Auth::user()->role_id;

        return Role::find($roleId);

        // return view('home', [
        //     'role' => $role
        // ]);
    }
}
