<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rsvp;

class RsvpController extends Controller
{
    public function rsvpRequest(Request $request)
    {
        $rsvp = Rsvp::where('user_id', auth()->user()->id)
            ->where('event_id', $request->input('event_id'))
            ->first();

        if (!$rsvp) {
            $rsvp = new Rsvp();
            $rsvp->event_id = $request->input('event_id');
            $rsvp->user_id = auth()->user()->id;
        }

        $rsvp->status_id = $request->input('status');
        $rsvp->save();


        return redirect()->route('index');
    }
}
