@extends('layouts/main')

@section('title', 'Events')

@section('main')
    {{-- header --}}
    <div class="card--max card__text grid__container">
        <h1 class="card--max">{{ $username }} – Parties</h1>
    </div>
    {{-- host's events --}}
    <div class="card--max card__text grid__container">
        <h2 class="card--max">
            Events {{ $isAuth ? "you're hosting" : ' hosted by ' . $username }}
        </h2>
        {{-- event list --}}
        <div class="card--max  grid__container">
            @if (count($events) === 0)
                <div class="card--max">
                    <p>Nothing yet!</p>
                    <p>
                        @if ($isAuth)
                            Start by <a href="{{ route('event.create') }}" class="nav-link">hosting an
                                event</a>
                        @else
                            {{ $username }} isn't hosting any upcoming events
                        @endif
                    </p>
                </div>
            @else
                @foreach ($events as $event)
                    <div class="card--max">
                        <a href="{{ route('event', ['event_id' => $event->id]) }}">
                            <p>{{ $event->title }}</p>
                        </a>
                        <p>{{ \Carbon\Carbon::parse($event->start)->diffForHumans() }}</p>
                    </div>
                @endforeach
            @endif
        </div>
    </div>

    {{-- rsvp'd events --}}
    @if ($isAuth)
        <div class="card--max card__text grid__container">
            <div class="grid__container card--max">
                <h2 class="card--max">Upcoming events</h2>
            </div>
            {{-- event list --}}
            <div class="grid__container card--max">
                <div class="card--max  grid__container">
                    @if (count($rsvps) === 0)
                        <div class="card--max">
                            <p>Aw shucks!</p>
                            <p>No upcoming events</p>
                        </div>
                    @else
                        @foreach ($rsvps as $rsvp)
                            <div class="card--max">
                                <p>RSVP'd {{ \Carbon\Carbon::parse($rsvp->updated_at)->diffForHumans(null, true) }} ago</p>
                                <a href="{{ route('event', ['event_id' => $rsvp->event_id]) }}">
                                    <p> {{ $rsvp->event->title }} </p>
                                </a>
                                <span class="badge bg-primary">{{ $rsvp->status->status }}</span>
                                <p>In {{ \Carbon\Carbon::parse($rsvp->event->start)->diffForHumans(null, true) }}</p>
                            </div>
                        @endforeach
                    @endif
                    </ul>
                </div>
            </div>
        </div>
    @endif

    {{-- past events --}}
    @if ($pastEvents->count() > 0 && $isAuth)
        <div class="card--max card__text grid__container">
            <div class="grid__container card--max">
                <h2 class="card--max">Past events</h2>
            </div>
            {{-- event list --}}
            <div class="card--max  grid__container">
                @foreach ($pastEvents as $pastEvent)
                    <div class="card--max">
                        <a href="{{ route('event', ['event_id' => $pastEvent->id]) }}">
                            <p>{{ $pastEvent->title }}</p>
                        </a>
                        <p>{{ \Carbon\Carbon::parse($pastEvent->start)->diffForHumans() }}</p>
                        <p>{{ $pastEvent->location }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
@endsection
