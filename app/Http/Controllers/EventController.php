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
    // events
    public function events($username)
    {
        $user_id = User::where('username', $username)->first()->id;
        return view(
            'events.events',
            [
                'username' => User::where('id', $user_id)->first()->name,
                'isAuth' => Auth::check() && Auth::user()->id === $user_id,
                'events' => Event::with(['user'])->where('user_id', $user_id)->orderBy('start', 'desc')->get(),
                'rsvps' => Rsvp::with(['user'])->whereIn('status_id', [1, 3])->where('user_id', $user_id)->get()->sortByDesc('start'),
            ]
        );
    }

    // event
    public function event($event_id)
    {
        return view(
            'events.event',
            [
                'event' => Event::find($event_id),
                'rsvpOptions' => Status::all(),
                'userRsvp' => Rsvp::where('event_id', $event_id)->where('user_id', Auth::id())->first(),
                'attendees' => Rsvp::with(['status'])->where('event_id', $event_id)->get(),
                'attendeesCount' => Rsvp::where('event_id', $event_id)->whereIn('status_id', [1, 3])->count(),
                'comments' => Comment::with(['user'])->where('event_id', $event_id)->orderBy('created_at', 'desc')->get(),
            ]
        );
    }

    // create event
    public function create()
    {
        return view(
            'events.event_create'
        );
    }

    public function createRequest(Request $request)
    {
        if (!Auth::check()) {
            // Store the post request in the session
            $request->session()->put('request', $request->all());
            $request->session()->put('intent', 'create');
            return redirect()->route('login');
        } else {
            $event = new Event();
            $event->title = $request->input('title');
            $event->description = $request->input('description');
            $event->start = $request->input('start');
            $event->end = $request->input('end');
            $event->location = $request->input('location');
            $event->user_id = Auth::id();
            $event->save();

            return redirect()->route('event', ['event_id' => $event->id]);
        }
    }

    // process create via get for redirect after login
    public function handleCreateRequest(Request $request)
    {
        $data = $request->session()->get('request');
        $request->session()->forget('request');

        // Simulate the request as if it's coming from a form submission
        $fakeRequest = new \Illuminate\Http\Request();
        $fakeRequest->replace($data);

        // Create new event
        $event = new Event();
        $event->title = $fakeRequest->input('title');
        $event->description = $fakeRequest->input('description');
        $event->start = $fakeRequest->input('start');
        $event->end = $fakeRequest->input('end');
        $event->location = $fakeRequest->input('location');
        $event->user_id = Auth::id();  // Ensure the user is logged in
        $event->save();

        $request->session()->forget(['request', 'intent']);

        return redirect()->route('event', ['event_id'
        => $event->id]);
    }


    // edit event
    public function edit($event_id)
    {
        if (Auth::user()->cannot('edit', Event::find($event_id))) {
            abort(403, 'Unauthorized action.');
        }

        return view(
            'events.event_edit',
            [
                'event' => Event::find($event_id),
            ]
        );
    }

    public function editRequest(Request $request, $event_id)
    {
        if (Auth::user()->cannot('edit', Event::find($event_id))) {
            abort(403, 'Unauthorized action.');
        }

        $event = Event::find($event_id);
        $event->title = $request->input('title');
        $event->description = $request->input('description');
        $event->start = $request->input('start');
        $event->end = $request->input('end');
        $event->location = $request->input('location');
        $event->save();

        return redirect()->route('event', [Event::find($event_id)]);
    }

    // delete event
    public function delete($event_id)
    {
        if (Auth::user()->cannot('delete', Event::find($event_id))) {
            abort(403, 'Unauthorized action.');
        }

        Event::destroy($event_id);
        return redirect()->route('index', ['username' => Auth::user()->username]);
    }
}
