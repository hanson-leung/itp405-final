@extends('layouts/main')

@section('title', 'Events')

@section('main')
    <h1 class="mb-3">
        {{ $isAuth ? 'Your' : Auth::user()->name . "'s" }}
        Events</h1>

    <ul class="list-group">

        @if (count($events) === 0)
            <li class="list-group-item">No upcoming events.</li>
        @else
            @foreach ($events as $event)
                <li class="list-group-item">
                    <a href="{{ route('event', ['event_id' => $event->id]) }}">{{ $event->title }}</a>
                    <p>{{ $event->start }}</p>
                </li>
            @endforeach
        @endif
    </ul>

    @if ($isAuth)
        <a href="{{ route('event.create') }}" class="btn btn-primary mt-3">Create Event</a>
        <h1 class="mt-5 mb-3">RSVPed</h1>

        <ul class="list-group">
            @if (count($rsvps) === 0)
                <li class="list-group-item">No upcoming events.</li>
            @else
                @foreach ($rsvps as $rsvp)
                    <li class="list-group-item">
                        <a href="{{ route('event', ['event_id' => $rsvp->event_id]) }}">{{ $rsvp->event->title }}</a>
                        <span class="badge bg-primary">{{ $rsvp->status->status }}</span>
                    </li>
                @endforeach
            @endif
        </ul>

    @endif
@endsection
