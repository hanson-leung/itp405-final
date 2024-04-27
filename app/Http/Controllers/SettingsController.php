<?php

namespace App\Http\Controllers;

use Auth;

class SettingsController extends Controller
{
    public function settings()
    {
        return view(
            'ciam.settings',
            [
                'user' => Auth::user(),
            ]
        );
    }

    public function settingsRequest()
    {
        // validate request
        request()->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . Auth::id(),
        ]);

        $user = Auth::user();
        $user->name = request()->input('name');
        $user->username = request()->input('username');
        $user->save();

        return redirect()->route('settings')->with('message', 'Account updated');
    }
}
