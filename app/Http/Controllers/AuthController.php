<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $credentials = $request->only('email', 'password');

        if (\Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'email' => 'Username atau password anda tidak sesuai.',
        ])->withInput($request->only('email'));
    }

    public function logout(Request $request)
    {
        \Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
