@extends('layouts/main')

@section('title', 'Events')

@section('main')
    <h1 class="mb-3">Events</h1>

    <ul class="list-group">
        @if (count($events) === 0)
            <li class="list-group-item">No events found.</li>
        @else
            @foreach ($events as $event)
                <li class="list-group-item">
                    <a href="{{ route('event', ['id' => $event->id]) }}">{{ $event->title }}</a>
                </li>
            @endforeach
        @endif
    </ul>

    <a href="{{ route('event.create') }}" class="btn btn-primary mt-3">Create Event</a>

@endsection
