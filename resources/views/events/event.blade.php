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

        @if ($attendeesCount > 0)
            <p>
                {{ $attendeesCount > 1 ? 'are' : 'is' }} going.
            </p>
        @endif
    @endif

    <hr>

    {{-- if owner: edit event, else rsvp --}}
    @if (Auth::check() && Auth::user()->id === $event->user_id)
        <a href="{{ route('event.edit', ['event_id' => $event->id]) }}" class="btn btn-primary">Edit</a>
    @else
        <form method="post" action="
        {{ route('rsvp.post') }}
        ">
            @csrf
            <div class="mb-3">
                <label class="form-label" for="status_id">Will you be there?</label>
                <select id="status_id" name="status_id" class="form-control">
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

    <hr>

    {{-- if logged in and rsvped or the event owner, leave and comment --}}
    @if (Auth::check())
        @if ($userRsvp || Auth::user()->id === $event->user_id)
            <form method="post" action="{{ route('comment.post') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label" for="comment">Comment</label>
                    <textarea id="comment" name="comment" class="form-control"></textarea>
                </div>
                <input type="hidden" name="event_id" value="{{ $event->id }}">
                <input type="submit" value="Submit" class="btn btn-primary">
            </form>
        @else
            <p>You must RSVP to leave a comment.</p>
        @endif
    @else
        <p><a href="{{ route('login', ['event_id' => $event->id]) }}">Login</a> to leave a comment.</p>
    @endif

    {{-- if comments, show comments --}}
    @if ($comments)
        <ul class="list-group">
            @foreach ($comments as $comment)
                <hr>
                <li class="list-group-item">
                    <p>{{ $comment->user->name }}</p>
                    <p>{{ $comment->comment }}</p>
                    <p>{{ $comment->created_at }}</p>
                </li>
                @if (Auth::user() && $comment->user->id === Auth::user()->id)
                    <form method="post" action="{{ route('comment.delete.post') }}">
                        @csrf
                        <input type="hidden" name="comment_id" value="{{ $comment->id }}">
                        <input type="submit" value="Delete" class="btn btn-danger">
                    </form>
                    <form
                        action="{{ route('comment.edit', ['comment_id' => $comment->id]) }}">
                        @csrf
                        <input type="submit" value="Edit" class="btn btn-primary">
                    </form>
                @endif
            @endforeach
        </ul>
    @endif
@endsection
