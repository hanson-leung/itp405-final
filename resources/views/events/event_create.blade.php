@extends('layouts/main')

@section('title', 'Create Event')

@section('main')
    <h1 class="mb-5">Create</h1>

    <form method="post" action="{{ route('event.create.post') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label" for="title">Title</label>
            <input type="text" id="title" name="title" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label
            " for="description">Description</label>
            <textarea id="description" name="description" class="form-control"></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label
            " for="start">Start</label>
            <input type="datetime-local" id="start" name="start" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label
            " for="end">End</label>
            <input type="datetime-local" id="end" name="end" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label
            " for="location">Location</label>
            <input type="text" id="location" name="location" class="form-control">
        </div>
        <input type="submit" value="Create" class="btn btn-primary">
    </form>
@endsection
