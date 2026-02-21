<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        if (session('is_admin')) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        if ($credentials['username'] === env('ADMIN_USERNAME') && 
            $credentials['password'] === env('ADMIN_PASSWORD')) {
            
            session(['is_admin' => true]);
            session()->save(); // Ensure session is written
            
            return redirect()->route('dashboard');
        }

        return back()->withErrors([
            'username' => 'The provided credentials do not match our records.',
        ])->withInput();
    }

    public function logout(Request $request)
    {
        session()->forget('is_admin');
        session()->invalidate();
        session()->regenerateToken();
        
        return redirect()->route('login');
    }
}
