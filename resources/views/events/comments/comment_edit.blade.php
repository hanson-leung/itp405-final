@extends('layouts/main')

@section('title', 'Event')

@section('main')
    <form method="post" action="{{ route('comment.edit.post', ['comment_id' => $comment->id]) }}">
        @csrf
        <div class="mb-3">
            <label class="form-label
        " for="comment">comment</label>
            <textarea id="comment" name="comment" class="form-control">{{ old('comment', $comment->comment) }}</textarea>
        </div>
        <input type="submit" value="Edit" class="btn btn-primary">
    </form>

@endsection
