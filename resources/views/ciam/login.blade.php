@extends('layouts/main')

@section('title', 'Login')

@section('main')
    <h1>Login</h1>

    <form method="post"
        action="{{ route('login.post', ['event_id' => $event_id ?? null, 'status_id' => $status_id ?? null]) }}">
        @csrf
        <div class="mb-3">
            <label class="form-label" for="phone">Phone</label>
            <input type="phone" id="phone" name="phone" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label" for="password">Password</label>
            <input type="password" id="password" name="password" class="form-control">
        </div>
        <input type="submit" value="Login" class="btn btn-primary">
    </form>
@endsection
