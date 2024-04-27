@extends('layouts/main')

@section('title', 'Login')

@section('main')

    <div class="card--max card__text grid__container">
        <h1 class="card--max">Login</h1>
        <p class="card--max">Get ready to party!</p>
    </div>

    <div class="card--max card__text grid__container">
        <a class="card--max" href="{{ route('login') }}">Not {{ $username->name }}?</a>
        <form method="post" action="{{ route('login.verify.post') }}" class="grid__container card--m">
            @csrf
            <div class="grid__content grid__content--fixed card--m">
                <div class="card--min">
                    <label class="form-label" for="phone">Phone</label>
                </div>
                <input type="text" id="phone" name="phone" class="form-control" value="{{ $phone }}"
                    disabled>
            </div>
            <div class="grid__content grid__content--fixed card--m">
                <div class="card--min">
                    <label class="form-label" for="password">Password</label>
                </div>
                <input type="password" id="password" name="password" class="form-control">
            </div>
            <input type="submit" value="Continue" class="btn btn-primary">
        </form>
    </div>
@endsection
