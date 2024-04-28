@extends('layouts/main')

@section('title', 'Login')

@section('main')
    {{-- header --}}
    <div class="card--max card__text grid__container">
        <h1 class="card--max">Login</h1>
        <p class="card--max">Get ready to party!</p>
    </div>

    {{-- login form --}}
    <div class="card--max card__text grid__container">
        <form method="post" action="{{ route('login.post') }}" class="grid__container card--m">
            @csrf
            <div class="grid__content grid__content--fixed card--m">
                <div class="card--min">
                    <label for="phone">Phone</label>
                </div>
                <input type="text" id="phone" name="phone" class="form-control" value="{{ old('phone') }}">
            </div>
            <input type="submit" value="Continue" class="card--max grid--start">
        </form>
    </div>
@endsection
