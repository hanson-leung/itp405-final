@extends('layouts/main')

@section('title', 'Login')

@section('main')
    <h1>Login</h1>

    <a href="{{ route('login') }}">Not {{ $username->name }}?</a>

    <form method="post" action="{{ route('login.verify.post') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label" for="phone">Phone</label>
            <input type="phone" id="phone" name="phone" class="form-control" value="{{ $phone }}" disabled>
        </div>
        <div class="mb-3">
            <label class="form-label" for="password">Password</label>
            <input type="password" id="password" name="password" class="form-control">
        </div>
        <input type="submit" value="Continue" class="btn btn-primary">
    </form>
@endsection
