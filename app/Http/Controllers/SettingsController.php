<?php

namespace App\Http\Controllers;

use Auth;

class SettingsController extends Controller
{
    // settings page
    public function settings()
    {
        // return view
        return view(
            'ciam.settings',
            [
                'user' => Auth::user(),
            ]
        );
    }


    // settings request
    public function settingsRequest()
    {
        // validate request
        request()->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . Auth::id(),
        ]);

        try {
            // update user
            $user = Auth::user();
            $user->name = request()->input('name');
            $user->username = request()->input('username');
            $user->save();

            // redirect to settings
            return redirect()->route('settings')->with('message', 'Account updated');
        } catch (\Exception $e) {
            return redirect()->route('settings')->with('message', 'Error updating account');
        }
    }
}
