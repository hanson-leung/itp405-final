@extends('layouts/main')

@section('title', '404 Error')

@section('main')
    {{-- header --}}
    <div class="card--max card__text grid__container">
        <h1 class="card--max">No parties here</h1>
        <p class="card--max">But it's not too late to <a href="{{ route('event.create') }}">host your own</a>!</p>
    </div>
@endsection
