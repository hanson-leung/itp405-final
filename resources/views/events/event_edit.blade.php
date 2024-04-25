@extends('layouts/main')

@section('title', 'Edit Event')

@section('main')
    <h1 class="mb-5">Edit</h1>

    <form method="post" action="{{ route('event.edit.post', ['id' => $event->id]) }}">
        @csrf
        <div class="mb-3">
            <label class="form-label" for="title">Title</label>
            <input type="text" id="title" name="title" class="form-control"
                value="{{ old('title') ?? ($event->title ?? '') }}">
        </div>
        <div class="mb-3">
            <label class="form-label
            " for="description">Description</label>
            <textarea id="description" name="description" class="form-control"
                value="{{ old('description') ?? ($event->description ?? '') }}"></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label
            " for="start">Start</label>
            <input type="datetime-local" id="start" name="start" class="form-control"
                value="{{ old('start') ?? ($event->start ?? '') }}">
        </div>
        <div class="mb-3">
            <label class="form-label
            " for="end">End</label>
            <input type="datetime-local" id="end" name="end" class="form-control"
                value="{{ old('end') ?? ($event->end ?? '') }}">
        </div>
        <div class="mb-3">
            <label class="form-label
            " for="location">Location</label>
            <input type="text" id="location" name="location" class="form-control"
                value="{{ old('location') ?? ($event->location ?? '') }}">
        </div>
        <input type="submit" value="Edit" class="btn btn-primary">
    </form>
@endsection
