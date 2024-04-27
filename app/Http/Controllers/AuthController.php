<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Contracts\Session\Session;

class AuthController extends Controller
{
    // logout
    public function logoutRequest()
    {
        Auth::logout();
        session()->flush();
        return redirect()->route('login')->with('message', 'Successfully logged out');
    }


    // login
    public function login()
    {
        if (Auth::check()) {
            return redirect()->route('index', ['username' => Auth::user()->username]);
        }

        if (session()->has('phone')) {
            session()->forget('phone');
        }

        return view(
            'ciam.login',
        );
    }


    // login request
    public function loginRequest(Request $request)
    {
        // validate phone
        $request->validate([
            'phone' => 'required|numeric|digits:10',
        ]);

        // store phone in session
        session()->put('phone', $request->input('phone'));

        // check if user exists
        $isReturningUser = User::where('phone', $request->input('phone'))->exists();
        if ($isReturningUser) {
            // if exists, redirect to login verify
            return redirect()->route('login.verify');
        } else {
            // if not exists, redirect to register
            return redirect()->route('register');
        }
    }

    // login verify
    public function loginVerify()
    {
        $isReturningUser = User::where('phone', session()->get('phone'))->exists();

        if (Auth::check()) {
            return redirect()->route('index', ['username' => Auth::user()->username]);
        } else if (!$isReturningUser) {
            return redirect()->route('register');
        }

        if (!session()->has('phone')) {
            return redirect()->route('login');
        }
        return view(
            'ciam.login_verify',
            [
                'phone' => session()->get('phone'),
                'username' => User::where('phone', session()->get('phone'))->first(),
            ]
        );
    }

    // login verify request
    public function loginVerifyRequest(Request $request)
    {
        // validate request
        $request->validate([
            'password' => 'required|string|max:255',
        ]);

        $wasLoginSuccessful = Auth::attempt([
            'phone' => session()->get('phone'),
            'password' => $request->input('password'),
        ]);

        if ($wasLoginSuccessful) {
            return redirect()->route('login.redirect');
        } else {
            return redirect()->route('login.verify')->with('message', 'Invalid password');
        }
    }

    public function loginRedirect()
    {
        if (session()->has('intent')) {
            $intent = session()->get('intent');
            if ($intent == 'rsvp') {
                return redirect()->route('rsvp.handle');
            } else if ($intent == 'create') {
                return redirect()->route('event.create.handle');
            }
        }
        return redirect()->route('index', ['username' => Auth::user()->username]);
    }
}
