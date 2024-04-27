<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use App\Models\Rsvp;

use Auth;

class RsvpController extends Controller
{
    public function rsvpRequest(Request $request)
    {
        // check if logged in
        if (!Auth::check()) {
            // Store the post request in the session
            $request->session()->put('request', $request->all());
            $request->session()->put('intent', 'rsvp');
            return redirect()->route('login');
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

    // process rsvp via get for redirect after login
    public function handleRsvp(Request $request)
    {
        $data = $request->session()->get('request');
        $request->session()->forget('request');

        $fakeRequest = new \Illuminate\Http\Request();
        $fakeRequest->replace($data);

        $rsvp = Rsvp::where('user_id', Auth::user()->id)
            ->where('event_id', $fakeRequest->input('event_id'))
            ->first();

        if (Auth::user()->id == Event::find($fakeRequest->input('event_id'))->user_id) {
            return redirect()->route('event', ['event_id' => $fakeRequest->input('event_id')]);
        }

        if (!$rsvp) {
            $rsvp = new Rsvp();
            $rsvp->event_id = $fakeRequest->input('event_id');
            $rsvp->user_id = Auth::user()->id;
        }

        $rsvp->status_id = $fakeRequest->input('status_id');
        $rsvp->save();

        $request->session()->forget(['request', 'intent']);

        return redirect()->route('event', ['event_id' => $fakeRequest->input('event_id')]);
    }
}
