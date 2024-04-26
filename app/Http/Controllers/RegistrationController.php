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
        return view('ciam.register');
    }


    // register request
    public function registerRequest(Request $request, $event_id, $rsvp_id)
    {
        $user = new User();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));
        $user->save();
        Auth::login($user);

        if ($event_id && $rsvp_id) {
            return redirect()->route('event', ['event_id' => $event_id, 'rsvp_id' => $rsvp_id]);
        } else {
            return redirect()->route('index', ['username' => Auth::user()->name]);
        }
    }
}
