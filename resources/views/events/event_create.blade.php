@extends('layouts/main')

@section('title', 'Create')

@section('main')
    {{-- header --}}
    <div class="card--max card__text grid__container">
        <h1 class="card--max">Create</h1>
    </div>

    {{-- create event form --}}
    <div class="card--max card__text grid__container">
        <form method="post" action="{{ route('event.create.post') }}" class="grid__container card--m">
            @csrf
            <div class="grid__content grid__content--fixed card--m">
                <div class="card--min">
                    <label class="form-label" for="title">Title</label>
                </div>
                <input type="text" id="title" name="title" class="form-control" value="{{ old('title') }}">
            </div>
            <div class="grid__content grid__content--fixed card--m">
                <div class="card--min">
                    <label class="form-label" for="description">Description</label>
                </div>
                <textarea id="description" name="description" class="form-control">{{ old('description') }}</textarea>
            </div>
            <div class="grid__content grid__content--fixed card--m">
                <div class="card--min">
                    <label class="form-label" for="start">Start</label>
                </div>
                <input type="datetime-local" id="start" name="start" class="form-control" value="{{ old('start') }}">
            </div>
            <div class="grid__content grid__content--fixed card--m">
                <div class="card--min">
                    <label class="form-label" for="end">End</label>
                </div>
                <input type="datetime-local" id="end" name="end" class="form-control" value="{{ old('end') }}">
            </div>
            <div class="grid__content grid__content--fixed card--m">
                <div class="card--min">
                    <label class="form-label" for="location">Location</label>
                </div>
                <input type="text" id="location" name="location" class="form-control" value="{{ old('location') }}">
            </div>
            <input type="submit" value="Create" class="">
        </form>
    @endsection
