@extends('layouts/main')

@section('title', 'Events')

@section('main')
    <div class="card--max card__text grid__container">
        <a href="{{ route('event.create') }}" class="card--max">Create an event</a>
        <h1 class="card--max">
            Events {{ $isAuth ? "you're hosting" : ' hosted by ' . $username }}
        </h1>


        <div class="card--max card__text grid__container">
            @if (count($events) === 0)
                <div class="card--max">
                    <h2>Aw man!</h2>
                    <p>No upcoming events.</p>
                </div>
            @else
                @foreach ($events as $event)
                    <div class="card--s">
                        <a href="{{ route('event', ['event_id' => $event->id]) }}">
                            <h2>{{ $event->title }}</h2>
                        </a>
                        <p>{{ \Carbon\Carbon::parse($event->start)->diffForHumans() }}</p>
                        <p>{{ $event->location }}</p>
                    </div>
                @endforeach
            @endif
        </div>
    </div>


    @if ($isAuth)
        <div class="card--max card__text grid__container">
            <div class="grid__container card--max">
                <h1 class="card--max">Upcoming events</h1>
            </div>

            <div class="grid__container card--max">
                <div class="card--max card__text grid__container">
                    @if (count($rsvps) === 0)
                        <div class="card--max">
                            <h2>Aw man!</h2>
                            <p>No upcoming events.</p>
                        </div>
                    @else
                        @foreach ($rsvps as $rsvp)
                            <div class="card--s">
                                <a
                                    href="{{ route('event', ['event_id' => $rsvp->event_id]) }}">{{ $rsvp->event->title }}</a>
                                <span class="badge bg-primary">{{ $rsvp->status->status }}</span>
                                <p>{{ \Carbon\Carbon::parse($rsvp->event->start)->diffForHumans(null, true) }}</p>
                            </div>
                        @endforeach
                    @endif
                    </ul>
                </div>
            </div>
    @endif
@endsection
