<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Contracts\Session\Session;

class AuthController extends Controller
{
    // process logout
    public function logoutRequest()
    {
        Auth::logout();
        session()->flush();
        return redirect()->route('login')->with('message', 'Successfully logged out');
    }


    // login page
    public function login()
    {
        // if user is already logged in, redirect to index
        if (Auth::check()) {
            return redirect()->route('index', ['username' => Auth::user()->username]);
        }

        // if phone is in session, remove it
        if (session()->has('phone')) {
            session()->forget('phone');
        }

        // return view
        return view(
            'ciam.login',
        );
    }


    // login request
    public function loginRequest(Request $request)
    {
        // validate
        $request->validate([
            'phone' => 'required|numeric|digits:10',
        ]);

        // store phone in session
        session()->put('phone', $request->input('phone'));

        // check if user exists
        $isReturningUser = User::where('phone', $request->input('phone'))->exists();
        if ($isReturningUser) {
            // if exists, redirect to login verify – step 2
            return redirect()->route('login.verify');
        } else {
            // if user does not exists, redirect to register
            return redirect()->route('register');
        }
    }

    // login verify page
    public function loginVerify()
    {
        // check if user phone number exists in database
        $isReturningUser = User::where('phone', session()->get('phone'))->exists();

        if (Auth::check()) {
            // if user is already logged in, redirect to index
            return redirect()->route('index', ['username' => Auth::user()->username]);
        } else if (!$isReturningUser) {
            // if user does not exists, redirect to register
            return redirect()->route('register');
        }

        // if phone is not in session, redirect to login – step 1
        if (!session()->has('phone')) {
            return redirect()->route('login');
        }

        // return view
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

        // attempt login
        $wasLoginSuccessful = Auth::attempt([
            'phone' => session()->get('phone'),
            'password' => $request->input('password'),
        ]);

        if ($wasLoginSuccessful) {
            // if login successful, redirect to login redirect
            return redirect()->route('login.redirect');
        } else {
            // if login unsuccessful, redirect to login verify
            return redirect()->route('login.verify')->with('message', 'Invalid password');
        }
    }

    // login redirect
    public function loginRedirect()
    {
        // if intent is in session, redirect to the intent
        if (session()->has('intent')) {
            $intent = session()->get('intent');
            if ($intent == 'rsvp') {
                return redirect()->route('rsvp.handle');
            } else if ($intent == 'create') {
                return redirect()->route('event.create.handle');
            }
        }

        // if user has no intent in session, redirect to index
        return redirect()->route('index', ['username' => Auth::user()->username]);
    }
}
