<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use Auth;

class EventController extends Controller
{
    // events
    public function events()
    {
        return view(
            'events.events',
            [
                'events' => Event::where('user_id', Auth::id())->get(),
            ]
        );
    }

    // event
    public function event($id)
    {
        return view(
            'events.event',
            [
                'event' => Event::find($id),
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

        return redirect()->route('events');
    }

    // edit event
    public function edit($id)
    {
        return view(
            'events.event_edit',
            [
                'event' => Event::find($id),
            ]
        );
    }

    public function editRequest(Request $request, $id)
    {
        $event = Event::find($id);
        $event->title = $request->input('title');
        $event->description = $request->input('description');
        $event->start = $request->input('start');
        $event->end = $request->input('end');
        $event->location = $request->input('location');
        $event->save();

        return redirect()->route('events');
    }

    // delete event
    public function delete($id)
    {
        Event::destroy($id);
        return redirect()->route('events');
    }
}
