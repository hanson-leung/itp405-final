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
@endsection
