<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Hash;
use Auth;

class RegistrationController extends Controller
{
    // register page
    public function register()
    {
        // if phone is not in session, redirect to login
        if (!session()->has('phone')) {
            return redirect()->route('login');
        }

        // return view
        return view(
            'ciam.register',
            [
                'phone' => session()->get('phone'),
            ]
        );
    }


    // register request
    public function registerRequest(Request $request)
    {
        // validate
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        // create user
        try {
            $user = new User();
            $user->name = $request->input('name');
            $user->username = $request->input('username');
            $user->phone = session()->get('phone');
            $user->password = Hash::make($request->input('password'));
            $user->save();

            // login user
            Auth::login($user);

            // redirect to login redirect
            return redirect()->route('login.redirect');
        } catch (\Exception $e) {
            // if failed, redirect to register
            return redirect()->back()->with('message', 'Registration failed. Please try again.');
        }
    }
}
