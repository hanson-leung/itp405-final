@extends('layouts/main')

@section('title', 'Events')

@section('main')
    <div class="card--max card__text grid__container">
        <h1 class="card--max">
            Events {{ $isAuth ? "you're hosting" : ' hosted by ' . $username }}
        </h1>


        <div class="card--max  grid__container">
            @if (count($events) === 0)
                <div class="card--max">
                    <p>Let get it started in here</p>
                    <p><a href="{{ route('event.create') }}" class="nav-link">Host an
                            event</a>.</p>
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


    @if ($isAuth)
        <hr class="card--max">
        <div class="card--max card__text grid__container">
            <div class="grid__container card--max">
                <h1 class="card--max">Upcoming events</h1>
            </div>

            <div class="grid__container card--max">
                <div class="card--max  grid__container">
                    @if (count($rsvps) === 0)
                        <div class="card--max">
                            <p>Where are my party people?</p>
                            <p>No upcoming events.</p>
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

    @if ($pastEvents->count() > 0 && $isAuth)
        <hr class="card--max">
        <div class="card--max card__text grid__container">
            <div class="grid__container card--max">
                <h1 class="card--max">Past events</h1>
            </div>

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
