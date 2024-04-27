@extends('layouts/main')

@section('title', 'Test Page')

@section('main')
    <form method="post" action="{{ route('settings.post') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label
        " for="name">Full name</label>
            <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $user->name) }}">
        </div>
        <div class="mb-3">
            <label class="form-label
        " for="username">Username</label>
            <input type="text" id="username" name="username" class="form-control"
                value="{{ old('username', $user->username) }}">
        </div>
        <div class="mb-3">
            <label class="form-label
        " for="phone">Phone</label>
            <input type="number" id="phone" name="phone" class="form-control"
                value="{{ old('phone', $user->phone) }}">
        </div>
        <input type="submit" value="Update" class="btn btn-primary">
    </form>



@endsection
