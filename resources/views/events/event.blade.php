@extends('layouts/main')

@section('title', 'Event')

@section('main')
    <h1 class="mb-5">{{ $event->title }}</h1>

    <p>{{ $event->description }}</p>
    <p>{{ $event->start }}</p>

    @if ($event->end)
        <p>{{ $event->end }}</p>
    @endif

    @if ($event->location)
        <p>{{ $event->location }}</p>
    @endif

    @if ($attendees)
        <h2>Attendees</h2>
        @foreach ($attendees->take(3) as $attendee)
            <p>{{ $attendee->user->name }}</p>
        @endforeach
        @if ($attendees->count() > 3)
            <p>and {{ $attendees->count() - 3 }} more.</p>
        @endif
    @endif

    @if (Auth::check() && Auth::user()->id === $event->user_id)
        <a href="{{ route('event.edit', ['id' => $event->id]) }}" class="btn btn-primary">Edit</a>
    @else
        <form method="post" action="{{ route('rsvp.post', ['id' => $event->id]) }}">
            @csrf
            <div class="mb-3">
                <label class="form-label" for="status">Will you be there?</label>
                <select id="status" name="status" class="form-control">
                    @foreach ($rsvpOptions as $rsvpOption)
                        <option value="{{ $rsvpOption->id }}" @if ($userRsvp && $userRsvp->status_id == $rsvpOption->id) selected @endif>
                            {{ $rsvpOption->status }}</option>
                    @endforeach
                </select>
            </div>
            <input type="hidden" name="event_id" value="{{ $event->id }}">
            <input type="submit" value="Submit" class="btn btn-primary">
        </form>
    @endif
@endsection
