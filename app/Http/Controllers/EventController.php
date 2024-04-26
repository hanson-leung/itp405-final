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
    public function events($username)
    {
        $user_id = User::where('name', $username)->first()->id;
        return view(
            'events.events',
            [
                'isAuth' => Auth::check() && Auth::user()->name === $username,
                'events' => Event::with(['user'])->where('user_id', $user_id)->get(),
                'rsvps' => Rsvp::with(['event'])->where('user_id', $user_id)->whereIn('status_id', [1, 2, 3])->get(),
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
                'comments' => Comment::all(),
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
        $event = new Event();
        $event->title = $request->input('title');
        $event->description = $request->input('description');
        $event->start = $request->input('start');
        $event->end = $request->input('end');
        $event->location = $request->input('location');
        $event->user_id = Auth::id();
        $event->save();

        return redirect()->route('index', ['username' => Auth::user()->name]);
    }

    // edit event
    public function edit($event_id)
    {
        return view(
            'events.event_edit',
            [
                'event' => Event::find($event_id),
            ]
        );
    }

    public function editRequest(Request $request, $event_id)
    {
        $event = Event::find($event_id);
        $event->title = $request->input('title');
        $event->description = $request->input('description');
        $event->start = $request->input('start');
        $event->end = $request->input('end');
        $event->location = $request->input('location');
        $event->save();

        return redirect()->route('index', ['username' => Auth::user()->name]);
    }

    // delete event
    public function delete($event_id)
    {
        Event::destroy($event_id);
        return redirect()->route('index', ['username' => Auth::user()->name]);
    }
}
