@extends('layouts/main')

@section('title', 'Event')

@section('main')
    {{-- event title --}}
    <h1 class="mb-5">{{ $event->title }}</h1>

    {{-- event host --}}
    <p>Hosted by {{ $event->user->name }}</p>

    {{-- event description --}}
    <p>{{ $event->description }}</p>

    {{-- event start --}}
    <p>{{ $event->start }}</p>

    {{-- event end --}}
    @if ($event->end)
        <p>{{ $event->end }}</p>
    @endif

    {{-- event location --}}
    @if ($event->location)
        <p>{{ $event->location }}</p>
    @endif

    {{-- attendee list --}}
    @if ($attendees)
        @foreach ($attendees->take(3) as $attendee)
            <p>{{ $attendee->user->name }}</p>
        @endforeach
        @if ($attendeesCount > 3)
            and {{ $attendeesCount - 3 }} more
        @endif
        {{ $attendeesCount > 1 ? 'are' : 'is' }} going.
        </p>
    @endif

    {{-- if owner: edit event, else rsvp --}}
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

    {{-- if comments, show comments --}}
    @if ($comments)
        <h2 class="mt-5">Comments</h2>
        <ul class="list-group">
            @foreach ($comments as $comment)
                <li class="list-group
                -item">
                    <p>{{ $comment->user->name }}</p>
                    <p>{{ $comment->comment }}</p>
                    <p>{{ $comment }}</p>
                </li>
                {{-- @if ($comment->user->id === Auth::user()->id)
                    <form method="post" action="{{ route('comment.delete.post', ['id' => $comment->id]) }}">
                        @csrf
                        <input type="submit" value="Delete" class="btn btn-danger">
                    </form>
                    <form method="post" action="{{ route('comment.edit.post', ['id' => $comment->id]) }}">
                        @csrf
                        <input type="submit" value="Edit" class="btn btn-primary">
                    </form>
                @endif --}}
            @endforeach
        </ul>
    @endif


    {{-- if logged in and rsvped, leave and comment --}}
    @if (Auth::check() && $userRsvp)
        <p>User is logged in and has RSVPed</p>
        {{-- <form method="post" action="{{ route('comment.post', ['id' => $event->id]) }}">
            @csrf
            <div class="mb-3">
                <label class="form-label" for="comment">Comment</label>
                <textarea id="comment" name="comment" class="form-control"></textarea>
            </div>
            <input type="hidden" name="event_id" value="{{ $event->id }}">
            <input type="submit" value="Submit" class="btn btn-primary">
        </form> --}}
    @endif

@endsection
