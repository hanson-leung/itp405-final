<?php

namespace App\Http\Controllers;

class ProfileController extends Controller
{
    public function index()
    {
        return view('test', [
            'user' => auth()->user(),
        ]);
    }
}
