<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        if (!auth()->attempt($request->validated())) {
            return back()->withErrors([
                'email' => 'Invalid credentials',
            ]);
        }
    
        $request->session()->regenerate();
    
        return redirect()->intended('/dashboard');
    }

    public function logout(Request $request)
    {
        auth()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}