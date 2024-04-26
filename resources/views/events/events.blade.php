@extends('layouts/main')

@section('title', 'Events')

@section('main')
    <h1 class="mb-3">
        Events {{ $isAuth ? 'you are hosting' : ' hosted by ' . $username }}</h1>

    <ul class="list-group">

        @if (count($events) === 0)
            <li class="list-group-item">No upcoming events.</li>
        @else
            @foreach ($events as $event)
                <li class="list-group-item">
                    <a href="{{ route('event', ['event_id' => $event->id]) }}">{{ $event->title }}</a>
                    <p>{{ $event->start }}</p>
                    <p>{{ $event->location }}</p>
                </li>
            @endforeach
        @endif
    </ul>

    @if ($isAuth)
        <a href="{{ route('event.create') }}" class="btn btn-primary mt-3">Create event</a>
        <h1 class="mt-5 mb-3">Upcoming events</h1>

        <ul class="list-group">
            @if (count($rsvps) === 0)
                <li class="list-group-item">No upcoming events.</li>
            @else
                @foreach ($rsvps as $rsvp)
                    <li class="list-group-item">
                        <a href="{{ route('event', ['event_id' => $rsvp->event_id]) }}">{{ $rsvp->event->title }}</a>
                        <span class="badge bg-primary">{{ $rsvp->status->status }}</span>
                        <p>{{ $rsvp->event->start }}</p>
                    </li>
                @endforeach
            @endif
        </ul>

    @endif
@endsection
