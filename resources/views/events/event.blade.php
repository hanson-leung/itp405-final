@extends('layouts/main')

@section('title', $event->title)

@section('main')
    <div class="card--max card__text grid__container">
        {{--  if event owner, show edit button --}}
        @if ($isEventOwner)
            <div class="grid__content">
                <a href="{{ route('event.edit', ['event_id' => $event->id]) }}" class="card--max">Edit event</a>
            </div>
        @endif

        {{-- event title --}}
        <h1 class="card--max">{{ $event->title }}</h1>
    </div>

    <div class="card--max card__text">
        {{-- event start --}}
        <p class="card--max">{{ \Carbon\Carbon::parse($event->start)->format('F j, Y \a\t g:i A') }}

            {{-- event end --}}
            @if ($event->end)
                @if (\Carbon\Carbon::parse($event->start)->isSameDay(\Carbon\Carbon::parse($event->end)))
                    to {{ \Carbon\Carbon::parse($event->end)->format('g:i A') }}
                @else
                    to {{ \Carbon\Carbon::parse($event->end)->format('F j, Y \a\t g:i A') }}
                @endif
            @endif
        </p>

        {{-- event host --}}
        <p>Hosted by
            <a href="{{ route('index', ['username' => $event->user->username]) }}">{{ $isEventOwner ? 'you' : $event->user->name }}
            </a>
        </p>



        {{-- event location --}}
        @if ($event->location)
            <p>·</p>
            <p class="card--max">{{ $event->location }}</p>
        @endif
    </div>

    <div class="card--max card__text grid__container">
        {{-- event description --}}
        <p class="card--max">{{ $event->description }}</p>
    </div>

    {{-- attendees --}}

    <div class="card--max grid__container card__text">
        {{-- show all attendees --}}
        <div class="grid__content card--max">
            <p>Guest list:</p> [
            @if ($attendees->count() === 0)
                <p>No one yet, spread the word</p>
            @endif

            @foreach ($attendees->take(2) as $attendee)
                <p>{{ $attendee->user->name }}</p>
            @endforeach
            @if ($attendees->count() > 2)
                <a id="guestlist" href="#">
                    <p>+ {{ $attendees->count() - 2 }} more</p>
                </a>
            @endif
            ]
        </div>
    </div>


    <div class="grid__space">
    </div>

    <div class="card--max card__text grid__container">
        {{-- if owner: edit event, else rsvp --}}
        @if (!$isEventOwner)
            <form method="post" action="{{ route('rsvp.post') }}" class="grid__container card--max">
                @csrf
                <div class="grid__content grid__content--fixed card--m">
                    <div class="card--min">
                        <label class="form-label" for="status_id">Going?</label>
                    </div>
                    <select id="status_id" name="status_id" class="form-control">
                        @foreach ($rsvpOptions as $rsvpOption)
                            <option value="{{ $rsvpOption->id }}" @if ($userRsvp && $userRsvp->status_id == $rsvpOption->id) selected @endif>
                                {{ $rsvpOption->status }}</option>
                        @endforeach
                    </select>

                    <input type="hidden" name="event_id" value="{{ $event->id }}">
                    <input type="submit" value="Let's rally!" class="btn btn-primary">
                </div>
            </form>
        @endif



        {{-- comment form --}}
        @if (Auth::check())
            @if ($userRsvp || Auth::user()->id === $event->user_id)
                <form method="post" action="{{ route('comment.post') }}" class="grid__container card--max">
                    @csrf
                    <label class="card--min" for="comment">Leave a comment</label>
                    <textarea id="comment" name="comment" class="card--max">{{ old('comment') }}</textarea>
                    <input type="hidden" name="event_id" value="{{ $event->id }}">
                    <input type="submit" value="Send" class="btn btn-primary">
                </form>
            @else
                <p class="card--max">RSVP to leave a comment!</p>
            @endif
        @else
            <p><a href="{{ route('login') }}">Login</a> to leave a comment!</p>
        @endif

        {{-- comments --}}
        @if ($comments)
            {{-- show all comments --}}
            @foreach ($comments as $comment)
                <hr class="card--max">
                <div class="card--max grid__container">
                    <p>{{ $isEventOwner ? 'You' : $event->user->name }}
                        @if (\Carbon\Carbon::parse($comment->created_at)->diffInHours() < 12)
                            ({{ \Carbon\Carbon::parse($comment->created_at)->diffForHumans() }})
                        @else
                            ({{ \Carbon\Carbon::parse($comment->created_at)->format('F j, Y \a\t g:i A') }})
                        @endif
                        @if (Auth::user() && $comment->user->id === Auth::user()->id)
                            {{-- edit comment --}}
                            – <a href="{{ route('comment.edit', ['comment_id' => $comment->id]) }}">Edit</a>
                        @endif
                    </p>
                    <p>{{ $comment->comment }}</p>
                </div>
            @endforeach
    </div>
    @endif


    {{-- guests --}}
    @if ($attendees)
        <div class="modal">
            <div class="modal-content grid__container">
                <div class="modal-background"></div>
                <a href="#" class="modal-close card__text grid__container">Close</a>
                <div class="card--max card__text grid__container">
                    <h2>Going ({{ $attendees->where('status_id', 1)->count() }})</h2>

                    @foreach ($attendees->where('status_id', 1) as $attendee)
                        <a
                            href="{{ route('index', ['username' => $attendee->user->username]) }}">{{ $attendee->user->name }}</a>
                    @endforeach
                </div>

                @if ($attendees->where('status_id', 3)->count() > 0)
                    <hr class="card--max">
                    <div class="card--max card__text grid__container">
                        <h2>Maybe ({{ $attendees->where('status_id', 3)->count() }})</h2>
                        @foreach ($attendees->where('status_id', 3) as $attendee)
                            <a
                                href="{{ route('index', ['username' => $attendee->user->username]) }}">{{ $attendee->user->name }}</a>
                        @endforeach
                    </div>

                @endif


                @if ($isEventOwner && $attendees->where('status_id', 2)->count() > 0)
                    <hr class="card--max">
                    <div class="card--max card__text grid__container">
                        <p>Not going ({{ $attendees->where('status_id', 2)->count() }})</p>
                        @foreach ($attendees->where('status_id', 2) as $attendee)
                            <a
                                href="{{ route('index', ['username' => $attendee->user->username]) }}">{{ $attendee->user->name }}</a>
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
        $('.modal-close, .modal-background').on('click', function() {
            $('.modal').hide();
        });
    </script>
@endsection
