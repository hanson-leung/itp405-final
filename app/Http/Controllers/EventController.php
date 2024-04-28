<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Status;
use App\Models\Rsvp;
use App\Models\Comment;
use App\Models\User;
use Auth;

class EventController extends Controller
{
    // events page (index)
    public function events($username)
    {
        // check if user exists
        if (!User::where('username', $username)->exists()) {
            abort(404, 'User not found.');
        }

        // get user id
        $user_id = User::where('username', $username)->first()->id;

        // return view
        return view(
            'events.events',
            [
                'username' => User::where('id', $user_id)->first()->name,
                'isAuth' => Auth::check() && Auth::user()->id === $user_id,
                'events' => Event::with(['user'])->where('user_id', $user_id)->where('start', '>', now())->orderBy('start', 'asc')->get(),
                'pastEvents' => Event::with(['user'])->where('user_id', $user_id)->where('start', '<', now())->orderBy('start', 'desc')->get(),
                'rsvps' => Rsvp::with(['user'])->whereIn('status_id', [1, 3])->where('user_id', $user_id)->get()->sortByDesc('start'),
            ]
        );
    }


    // event page
    public function event($event_id)
    {
        // check if event exists
        if (!Event::where('id', $event_id)->exists()) {
            abort(
                404,
                'Event not found.'
            );
        }

        // return view
        return view('events.event', [
            'isEventOwner' => Auth::check() && Auth::user()->id === Event::find($event_id)->user_id,
            'event' => Event::find($event_id),
            'rsvpOptions' => Status::all(),
            'userRsvp' => Rsvp::where('event_id', $event_id)->where('user_id', Auth::id())->first(),
            'attendees' => Rsvp::with('user')->where('event_id', $event_id)->whereNotIn('status_id', [2])->get(),
            'comments' => Comment::with('user')->where('event_id', $event_id)->orderBy('created_at', 'desc')->get(),
        ]);
    }


    // create event page
    public function create()
    {
        // return view
        return view(
            'events.event_create'
        );
    }

    // create event request
    public function createRequest(Request $request)
    {
        // validate request
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'string|nullable',
            'start' => 'required|date|after:now',
            'end' => 'date|after:start|nullable',
            'location' => 'string|max:255|nullable',
        ]);

        // check if user is logged in
        if (!Auth::check()) {
            // store form inputs in session
            $request->session()->put('request', $request->all());
            $request->session()->put('intent', 'create');

            // redirect to login
            return redirect()->route('login');
        } else {
            // create new event
            try {
                $event = new Event();
                $event->title = $request->input('title');
                $event->description = $request->input('description');
                $event->start = $request->input('start');
                $event->end = $request->input('end');
                $event->location = $request->input('location');
                $event->user_id = Auth::id();
                $event->save();

                // redirect to event
                return redirect()->route('event', ['event_id' => $event->id])->with('message', 'Event created');
            } catch (\Exception $e) {
                return redirect()->route('event.create')->with('message', 'Error creating event');
            }
        }
    }


    // handle create request (after login)
    public function handleCreateRequest(Request $request)
    {
        // create new event
        try {
            // get event details from session
            $data = $request->session()->get('request');

            // simulate post request
            $fakeRequest = new \Illuminate\Http\Request();
            $fakeRequest->replace($data);

            $event = new Event();
            $event->title = $fakeRequest->input('title');
            $event->description = $fakeRequest->input('description');
            $event->start = $fakeRequest->input('start');
            $event->end = $fakeRequest->input('end');
            $event->location = $fakeRequest->input('location');
            $event->user_id = Auth::id();
            $event->save();

            // reset session request and intent
            $request->session()->forget(['request', 'intent']);

            // redirect to event
            return redirect()->route('event', ['event_id'
            => $event->id])->with('message', 'Event created');
        } catch (\Exception $e) {
            return redirect()->route('event.create')->with('message', 'Error creating event');
        }
    }


    // edit event page
    public function edit($event_id)
    {
        // check if user is authorized
        if (Auth::user()->cannot('edit', Event::find($event_id))) {
            abort(403, 'Unauthorized action.');
        }

        // return view
        return view(
            'events.event_edit',
            [
                'event' => Event::find($event_id),
            ]
        );
    }


    // edit event request
    public function editRequest(Request $request, $event_id)
    {
        // check if user is authorized
        if (Auth::user()->cannot('edit', Event::find($event_id))) {
            abort(403, 'Unauthorized action.');
        }

        // validate
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'string|nullable',
            'start' => 'required|date|after:now',
            'end' => 'date|after:start|nullable',
            'location' => 'string|max:255|nullable',
        ]);

        // update event
        try {
            $event = Event::find($event_id);
            $event->title = $request->input('title');
            $event->description = $request->input('description');
            $event->start = $request->input('start');
            $event->end = $request->input('end');
            $event->location = $request->input('location');
            $event->save();

            // redirect to event
            return redirect()->route('event', [Event::find($event_id)])->with('message', 'Event updated');
        } catch (\Exception $e) {
            return redirect()->route('event.edit', ['event_id' => $event_id])->with('message', 'Error updating event');
        }
    }


    // delete event
    public function delete(Request $request)
    {
        // get event id and name
        $event_id = $request->input('event_id');
        $event_name = Event::find($event_id)->title;

        // check if user is authorized
        if (Auth::user()->cannot('delete', Event::find($event_id))) {
            abort(403, 'Unauthorized action.');
        }

        // delete event
        try {
            Rsvp::where('event_id', $event_id)->delete();
            Comment::where('event_id', $event_id)->delete();
            Event::destroy($event_id);

            // redirect to index
            return redirect()->route('index', ['username' => Auth::user()->username])->with('message', $event_name . ' deleted!');
        } catch (\Exception $e) {
            return redirect()->route('event', ['event_id' => $event_id])->with('message', 'Error deleting event');
        }
    }
}
