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
        session()->flush();
        return redirect()->route('login');
    }


    // login
    public function login()
    {
        return view(
            'ciam.login',
        );
    }


    // login request
    public function loginRequest(Request $request)
    {
        $wasLoginSuccessful = Auth::attempt([
            'phone' => $request->input('phone'),
            'password' => $request->input('password'),
        ]);

        if ($wasLoginSuccessful) {
            if ($request->session()->has('intent')) {
                $intent = $request->session()->get('intent');
                if ($intent == 'rsvp') {
                    return redirect()->route('rsvp.handle');
                } else if ($intent == 'create') {
                    return redirect()->route('event.create.handle');
                }
            }
            return redirect()->route('index', ['username' => Auth::user()->username]);
        } else {
            return redirect()->route('login')->with('error', 'Invalid phone or password');
        }
    }
}
