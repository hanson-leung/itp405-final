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
        $user = Auth::user();
        $user->name = request()->input('name');
        $user->username = request()->input('username');
        $user->phone = request()->input('phone');
        $user->save();

        return redirect()->route('settings');
    }
}
