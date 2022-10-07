<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;

trait RedirectTo
{
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    public function redirectTo()
    {
        $role = Auth::user()->roles->first()->name;
        // dd($role);
        if ($role === "admin") {
            return '/admin/dashboard';
        } else {
            return '/';
        }
    }
}
