<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use App\Models\Rsvp;
use App\Models\Status;
use Auth;

class RsvpController extends Controller
{
    // rsvp request
    public function rsvpRequest(Request $request)
    {
        // validate
        $request->validate([
            'status_id' => 'required|numeric',
            'event_id' => 'required|numeric',
        ]);

        // check if user is logged in
        if (!Auth::check()) {
            // store request in session
            $request->session()->put('request', $request->all());
            $request->session()->put('intent', 'rsvp');

            // redirect to login
            return redirect()->route('login');
        } else {
            try {
                // get rsvp status and event name
                $rsvpStatus = Status::find($request->input('status_id'));
                $eventName = Event::find($request->input('event_id'))->title;

                // if already rsvp'd, update status
                $rsvp = Rsvp::where('user_id', Auth::user()->id)
                    ->where('event_id', $request->input('event_id'))
                    ->first();

                // if no rsvp, create a new entry
                if (!$rsvp) {
                    $rsvp = new Rsvp();
                    $rsvp->event_id = $request->input('event_id');
                    $rsvp->user_id = Auth::user()->id;
                }

                // save rsvp
                $rsvp->status_id = $request->input('status_id');
                $rsvp->save();

                // redirect to event
                return redirect()->route('event', ['event_id' => $request->input('event_id')])
                    ->with('message', "You've RSVP'd " . strtolower($rsvpStatus->status) . " to " . $eventName);
            } catch (\Exception $e) {
                return redirect()->route('event', ['event_id' => $request->input('event_id')])
                    ->with('message', 'Error RSVPing');
            }
        }
    }

    // handle rsvp request (after login)
    public function handleRsvp(Request $request)
    {
        // rsvp
        try {
            // get rsvp data from session
            $data = $request->session()->get('request');

            // simulate post request
            $fakeRequest = new \Illuminate\Http\Request();
            $fakeRequest->replace($data);

            // check if user is trying to rsvp to their own event
            if (Auth::user()->id == Event::find($fakeRequest->input('event_id'))->user_id) {
                return redirect()->route(
                    'event',
                    ['event_id' => $fakeRequest->input('event_id')]
                )->with('message', "You can't RSVP to your own event!");
            }

            // check if user already rsvp'd
            $rsvp = Rsvp::where('user_id', Auth::user()->id)
                ->where('event_id', $fakeRequest->input('event_id'))
                ->first();

            // if already rsvp'd, update status
            if (!$rsvp) {
                $rsvp = new Rsvp();
                $rsvp->event_id = $fakeRequest->input('event_id');
                $rsvp->user_id = Auth::user()->id;
            }

            // get rsvp status and event name
            $rsvpStatus = strtolower(Status::find($fakeRequest->input('status_id'))->status);
            $eventName = Event::find($fakeRequest->input('event_id'))->title;

            // if no rsvp, create a new entry
            $rsvp->status_id = $fakeRequest->input('status_id');
            $rsvp->save();

            // reset session request and intent
            $request->session()->forget(['request', 'intent']);

            // redirect to event
            return redirect()->route('event', ['event_id' => $fakeRequest->input('event_id')])
                ->with('message', "You've RSVP'd " . $rsvpStatus . " to " . $eventName);
        } catch (\Exception $e) {
            return redirect()->route('event', ['event_id' => $fakeRequest->input('event_id')])
                ->with('message', 'Error RSVPing');
        }
    }
}
