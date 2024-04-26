<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rsvp;

use Auth;

class RsvpController extends Controller
{
    public function rsvpRequest(Request $request)
    {
        // check if logged in
        if (!Auth::check()) {
            return redirect()->route('login', ['event_id' => $request->input('event_id'), 'status_id' => $request->input('status_id')]);
        } else {
            // if already rsvp'd, update status
            $rsvp = Rsvp::where('user_id', Auth::user()->id)
                ->where('event_id', $request->input('event_id'))
                ->first();

            // if no rsvp, create new
            if (!$rsvp) {
                $rsvp = new Rsvp();
                $rsvp->event_id = $request->input('event_id');
                $rsvp->user_id = Auth::user()->id;
            }

            $rsvp->status_id = $request->input('status_id');

            $rsvp->save();

            return redirect()->route('event', ['event_id' => $request->input('event_id')]);
        }
    }
}
