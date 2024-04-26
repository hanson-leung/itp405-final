@extends('layouts/main')

@section('title', 'Test Page')

@section('main')
    <h1 class="mb-3">Hi {{ $user->name }}</h1>


    <p>Username: {{ $user->username }}</p>
    <p>Email: {{ $user->email }}</p>


    {{-- <a href="{{ route('profile.edit', $user->id) }}" class="btn btn-primary">Edit Profile</a> --}}

@endsection
