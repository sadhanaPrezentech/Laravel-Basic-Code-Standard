<?php

namespace App\Http\Controllers;

use App\Traits\RedirectTo;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $role = Auth::user()->roles->first()->name;

        if ($role === "admin") {
            return view('admin.dashboard');
        } else {
            return view('home');
        }
        return view('home');
    }
}
