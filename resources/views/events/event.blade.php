@extends('layouts/main')

@section('title', $event->title)

@section('main')
    <div class="card--max card__text grid__container">
        {{-- show edit button if event owner --}}
        @if ($isEventOwner)
            <a href="{{ route('event.edit', ['event_id' => $event->id]) }}" class="card--max">Edit event</a>
        @endif

        {{-- event title --}}
        <h1 class="card--max">{{ $event->title }}</h1>
    </div>

    <div class="card--max card__text grid__container">
        <div class="card--max">
            {{-- event host --}}
            <p>Hosted by {{ $isEventOwner ? 'you' : $event->user->name }}</p>

            {{-- event start --}}
            {{ \Carbon\Carbon::parse($event->start)->format('F j, Y \a\t g:i A') }}

            {{-- event end --}}
            @if ($event->end)
                @if (\Carbon\Carbon::parse($event->start)->isSameDay(\Carbon\Carbon::parse($event->end)))
                    to {{ \Carbon\Carbon::parse($event->end)->format('g:i A') }}
                @else
                    to {{ \Carbon\Carbon::parse($event->end)->format('F j, Y \a\t g:i A') }}
                @endif
            @endif

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
                    @foreach ($attendees->take(2) as $attendee)
                        {{ $attendee->user->name }} @if ($attendeesCount > 2 && !$loop->last)
                            ,
                        @elseif ($loop->last)
                        @else
                            and
                        @endif
                    @endforeach
                    @if ($attendeesCount > 2)
                        and {{ $attendeesCount - 2 }} other
                    @endif
                    @if ($attendeesCount > 0)
                        {{ $attendeesCount > 1 ? 'are' : 'is' }} attending.
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


        {{-- comment form --}}
        @if (Auth::check())
            @if ($userRsvp || Auth::user()->id === $event->user_id)
                <form method="post" action="{{ route('comment.post') }}" class="grid__container card--m">
                    @csrf
                    <div class="grid__content grid__content--fixed card--m">
                        <label class="card--min" for="comment">Comment</label>
                        <textarea id="comment" name="comment" class="card--max">{{ old('comment') }}</textarea>
                    </div>
                    <input type="hidden" name="event_id" value="{{ $event->id }}">
                    <input type="submit" value="Submit" class="btn btn-primary">
                </form>
            @else
                <p>You must RSVP to leave a comment.</p>
            @endif
        @else
            <p><a href="{{ route('login') }}">Login</a> to leave a comment.</p>
        @endif
    </div>

    {{-- comments --}}
    <div class="card--max card__text grid__container">
        @if ($comments)
            <ul class="grid__container card--max">
                {{-- show all comments --}}
                @foreach ($comments as $comment)
                    <div class="card--max">
                        <p>{{ $isEventOwner ? 'You' : $event->user->name }}
                            @if (\Carbon\Carbon::parse($comment->created_at)->diffInHours() < 12)
                                ({{ \Carbon\Carbon::parse($comment->created_at)->diffForHumans() }})
                            @else
                                ({{ \Carbon\Carbon::parse($comment->created_at)->format('F j, Y \a\t g:i A') }})
                            @endif
                            @if (Auth::user() && $comment->user->id === Auth::user()->id)
                                {{-- edit comment --}}
                                <a href="{{ route('comment.edit', ['comment_id' => $comment->id]) }}">Edit</a>
                            @endif
                        </p>
                        <p>{{ $comment->comment }}</p>
                    </div>
                @endforeach
            </ul>
        @endif
    </div>

@endsection
