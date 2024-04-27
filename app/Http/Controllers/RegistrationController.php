<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Hash;
use Auth;

class RegistrationController extends Controller
{
    // register
    public function register()
    {
        if (!session()->has('phone')) {
            return redirect()->route('login');
        }

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
        // validate request
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        try {
            $user = new User();
            $user->name = $request->input('name');
            $user->username = $request->input('username');
            $user->phone = session()->get('phone');
            $user->password = Hash::make($request->input('password'));
            $user->save();

            Auth::login($user);

            return redirect()->route('login.redirect');
        } catch (\Exception $e) {
            return redirect()->back()->with('message', 'Registration failed. Please try again.');
        }
    }
}
