<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class AuthController extends Controller
{
    // logout
    public function logoutRequest()
    {
        Auth::logout();
        return redirect()->route('login');
    }


    // login
    public function login()
    {
        return view('ciam.login');
    }



    // login request
    public function loginRequest(Request $request)
    {
        $wasLoginSuccessful = Auth::attempt([
            'email' => $request->input('email'),
            'password' => $request->input('password'),
        ]);

        if ($wasLoginSuccessful) {
            return redirect()->route('index', ['username' => Auth::user()->name]);
        } else {
            return redirect()->route('login')->with('error', 'Invalid email or password');
        }
    }
}
