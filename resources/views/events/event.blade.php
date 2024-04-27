@extends('layouts/main')

@section('title', 'Event')

@section('main')
    <div class="card--max card__text grid__container">
        {{-- event title --}}
        <h1 class="card--max">{{ $event->title }}</h1>

        {{-- show edit button if event owner --}}
        @if ($isEventOwner)
            <a href="{{ route('event.edit', ['event_id' => $event->id]) }}" class="card--max">Edit</a>
        @endif
    </div>

    <div class="card--max card__text grid__container">
        <div class="card--max">
            {{-- event host --}}
            <p>Hosted by {{ $isEventOwner ? 'you' : $event->user->name }}</p>

            {{-- event start --}}
            <p>{{ $event->start }}

                {{-- event end --}}
                @if ($event->end)
                    to {{ $event->end }}
                @endif
            </p>

            {{-- event description --}}
            <p>{{ $event->description }}</p>

            {{-- event location --}}
            @if ($event->location)
                <p>{{ $event->location }}</p>
            @endif

        </div>
        <div class="card--max">
            <p>
                {{-- attendee list --}}
                @if ($attendees)
                    @foreach ($attendees->take(3) as $attendee)
                        {{ $attendee->user->name }}
                    @endforeach
                    @if ($attendeesCount > 3)
                        and {{ $attendeesCount - 3 }} more
                    @endif

                    @if ($attendeesCount > 0)
                        {{ $attendeesCount > 1 ? 'are' : 'is' }} going.
                    @endif
                @endif
            </p>
        </div>
    </div>


    <div class="card--max card__text grid__container">
        {{-- if owner: edit event, else rsvp --}}
        @if (!$isEventOwner)
            <form method="post" action="{{ route('rsvp.post') }}" class="grid__container card--m">
                @csrf
                <div class="grid__content grid__content--fixed card--m">
                    <div class="card--min">
                        <label class="form-label" for="status_id">Will you be there?</label>
                    </div>
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


        {{-- if logged in and rsvped or the event owner, leave and comment --}}
        @if (Auth::check())
            @if ($userRsvp || Auth::user()->id === $event->user_id)
                <form method="post" action="{{ route('comment.post') }}" class="grid__container card--m">
                    @csrf
                    <div class="grid__content grid__content--fixed card--m">
                        <label class="card--min" for="comment">Comment</label>
                        <textarea id="comment" name="comment" class=""></textarea>
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
    </div>

    <div class="card--max card__text grid__container">

        {{-- if comments, show comments --}}
        @if ($comments)
            <ul class="grid__container card--max">
                @foreach ($comments as $comment)
                    <div class="card--max">
                        <p>{{ $isEventOwner ? 'You' : $event->user->name }} at <span>{{ $comment->created_at }}</span></p>
                        <p>{{ $comment->comment }}</p>
                    </div>
                    @if (Auth::user() && $comment->user->id === Auth::user()->id)
                        <div class="card--s grid__container">
                            <form action="{{ route('comment.edit', ['comment_id' => $comment->id]) }}">
                                @csrf
                                <input type="submit" value="Edit" class="btn btn-primary">
                            </form>
                            <form method="post" action="{{ route('comment.delete.post') }}">
                                @csrf
                                <input type="hidden" name="comment_id" value="{{ $comment->id }}">
                                <input type="submit" value="Delete" class="btn btn-danger">
                            </form>
                        </div>
                    @endif
                @endforeach
            </ul>
        @endif
    </div>
@endsection
