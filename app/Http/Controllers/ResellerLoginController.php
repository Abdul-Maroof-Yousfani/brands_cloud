<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResellerLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('reseller.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('reseller')->attempt(['email' => $request->email, 'password' => $request->password], $request->remember)) {
            // Check if user is actually logged in
            if (Auth::guard('reseller')->check()) {
                return redirect()->route('reseller.dashboard');
            } else {
                return redirect()->back()->withErrors(['email' => 'Session could not be persisted.']);
            }
        }

        // If attempt fails:
        return redirect()->back()->withInput($request->only('email', 'remember'))->withErrors([
            'email' => 'These credentials do not match our records.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('reseller')->logout();
        $request->session()->invalidate();
        return redirect()->route('reseller.login');
    }

    public function dashboard()
    {
        return view('reseller.dashboard');
    }
}
