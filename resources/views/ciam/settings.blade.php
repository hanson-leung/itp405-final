@extends('layouts/main')

@section('title', 'Account')

@section('main')
    {{-- header --}}
    <div class="card--max card__text grid__container">
        <h1 class="card--max">Account</h1>
    </div>

    {{-- account form --}}
    <div class="card--max card__text grid__container">
        <form method="post" action="{{ route('settings.post') }}" class="grid__container card--m">
            @csrf
            <div class="grid__content grid__content--fixed card--m">
                <div class="card--min">
                    <label class="form-label" for="name">Full name</label>
                </div>
                <input type="text" id="name" name="name" class="form-control"
                    value="{{ old('name', $user->name) }}">
            </div>
            <div class="grid__content grid__content--fixed card--m">
                <div class="card--min">
                    <label class="form-label " for="username">Username</label>
                </div>
                <input type="text" id="username" name="username" class="form-control"
                    value="{{ old('username', $user->username) }}">
            </div>
            <div class="grid__content grid__content--fixed card--m">
                <div class="card--min">
                    <label class="form-label" for="phone">Phone</label>
                </div>
                <input type="number" id="phone" name="phone" class="form-control"
                    value="{{ old('phone', $user->phone) }}" disabled>
            </div>
            <input type="submit" value="Update" class="btn btn-primary">
        </form>
    </div>

    {{-- logout form --}}
    <div class="card--max card__text grid__container">
        <form method="post" action="{{ route('logout.post') }}">
            @csrf
            <button type="submit" class="btn btn-danger nav-link">Logout</button>
        </form>
    </div>
@endsection
