@extends('layouts/main')

@section('title', '403 Error')

@section('main')
    {{-- header --}}
    <div class="card--max card__text grid__container">
        <h1 class="card--max">House rules</h1>
        <p class="card--max">This page is behind closed doors, <a href="{{ route('login') }}">log in</a></p>
    </div>
@endsection
