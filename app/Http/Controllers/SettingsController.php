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
}
