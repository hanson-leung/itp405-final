@extends('layouts/main')

@section('title', 'Edit Event')

@section('main')
    <div class="card--max card__text grid__container">
        <h1 class="card--max">Edit event</h1>
    </div>

    <div class="card--max card__text grid__container">
        <form method="post" action="{{ route('event.edit.post', ['event_id' => $event->id]) }}"
            class="grid__container card--m">
            @csrf
            <div class="grid__content grid__content--fixed card--m">
                <div class="card--min">
                    <label class="form-label" for="title">Title</label>
                </div>
                <input type="text" id="title" name="title" class="form-control"
                    value="{{ old('title') ?? ($event->title ?? '') }}">
            </div>
            <div class="grid__content grid__content--fixed card--m">
                <div class="card--min">
                    <label class="form-label" for="description">Description</label>
                </div>
                <textarea id="description" name="description" class="form-control">{{ old('description') ?? ($event->description ?? '') }}</textarea>
            </div>
            <div class="grid__content grid__content--fixed card--m">
                <div class="card--min">
                    <label class="form-label" for="start">Start</label>
                </div>
                <input type="datetime-local" id="start" name="start" class="form-control"
                    value="{{ old('start') ?? ($event->start ?? '') }}">
            </div>
            <div class="grid__content grid__content--fixed card--m">
                <div class="card--min">
                    <label class="form-label" for="end">End</label>
                </div>
                <input type="datetime-local" id="end" name="end" class="form-control"
                    value="{{ old('end') ?? ($event->end ?? '') }}">
            </div>
            <div class="grid__content grid__content--fixed card--m">
                <div class="card--min">
                    <label class="form-label" for="location">Location</label>
                </div>
                <input type="text" id="location" name="location" class="form-control"
                    value="{{ old('location') ?? ($event->location ?? '') }}">
            </div>
            <input type="submit" value="Save" class="btn btn-primary">
        </form>
    </div>

    {{-- delete event --}}
    <div class="card--max card__text grid__container">
        <form method="post" action="{{ route('event.delete.post') }}" class="grid__container card--m">
            @csrf
            <input type="hidden" name="event_id" value="{{ $event->id }}">
            <input type="submit" value="Delete" class="btn btn-danger">
        </form>
    </div>
@endsection
