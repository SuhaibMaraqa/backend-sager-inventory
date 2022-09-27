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

    public function users()
    {
        return User::all();
    }

    public function index()
    {
        if (Auth::user()->isAdmin) {
            $roleId = 1;
        } elseif (Auth::user()->isCenter) {
            $roleId = 2;
        } else {
            $roleId = 3;
        }

        return Role::find($roleId);
    }
}
