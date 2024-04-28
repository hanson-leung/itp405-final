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
        @if ($attendees)
            <p>Guest list ({{ $attendees->where('status_id', 1)->count() }} going,
                {{ $attendees->where('status_id', 3)->count() }} maybe)</p>
            <div class="card--max grid__container">
                {{-- show all attendees --}}
                <a id="guestlist" href="#" class="grid__content">
                    @foreach ($attendees->take(2) as $attendee)
                        <p>{{ $attendee->user->name }}</p>
                    @endforeach
                    @if ($attendees->count() > 2)
                        <p>+ {{ $attendees->count() - 2 }} more</p>
                    @endif
                </a>
            </div>
        @endif
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
    @if ($comments)
        <div class="card--max card__text grid__container">

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
        </div>
    @endif


    {{-- guests --}}
    @if ($attendees)
        <div class="modal">
            <div class="modal-content">
                <span class="modal-close">&times;</span>
                <div class="card--max card__text grid__container">
                    <p>Going</p>
                    @foreach ($attendees->where('status_id', 1) as $attendee)
                        <p>{{ $attendee->user->name }}</p>
                    @endforeach
                </div>

                <div class="card--max card__text grid__container">
                    <p>Maybe</p>
                    @foreach ($attendees->where('status_id', 3) as $attendee)
                        <p>{{ $attendee->user->name }}</p>
                    @endforeach
                </div>

                @if ($isEventOwner)
                    <div class="card--max card__text grid__container">
                        <p>Not going</p>
                        @foreach ($attendees->where('status_id', 2) as $attendee)
                            <p>{{ $attendee->user->name }}</p>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    @endif

    <script>
        $('#guestlist').on('click', function() {
            $('.modal').show()
        })
        $('.modal-close').on('click', function() {
            $('.modal').hide()
        })
    </script>
@endsection
