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
        return view(
            'ciam.login',
            [
                'event_id' => request('event_id'),
                'status_id' => request('status_id'),
            ]
        );
    }


    // login request
    public function loginRequest(Request $request, $event_id = null, $status_id = null)
    {
        $wasLoginSuccessful = Auth::attempt([
            'email' => $request->input('email'),
            'password' => $request->input('password'),
        ]);

        if ($wasLoginSuccessful) {
            if ($event_id && $status_id) {
                return redirect()->route('event', ['event_id' => $event_id, 'status_id' => $status_id])->withInput();
            } else {
                return redirect()->route('index', ['username' => Auth::user()->name]);
            }
        } else {
            return redirect()->route('login')->with('error', 'Invalid email or password');
        }
    }
}
