@extends('layouts/main')

@section('title', 'Test Page')

@section('main')
    <h1 class="mb-3">Hi {{ $user->name }}</h1>


    @if (Auth::check())
        <p>Email: {{ $user->email }}</p>
    @else
        <p>You are not logged in.</p>
    @endif

    {{-- <a href="{{ route('profile.edit', $user->id) }}" class="btn btn-primary">Edit Profile</a> --}}

@endsection
