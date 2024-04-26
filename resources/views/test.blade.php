@extends('layouts/main')

@section('title', 'Test Page')

@section('main')
    <h1 class="mb-3">Hello World</h1>

    {{-- 
    @if (Auth::check())
        <p>Name: {{ $user->name }}</p>
        <p>Email: {{ $user->email }}</p>
    @else
        <p>You are not logged in.</p>
    @endif --}}

@endsection
