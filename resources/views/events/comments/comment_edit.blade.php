@extends('layouts/main')

@section('title', 'Event')

@section('main')
    {{-- edit comment form --}}
    <div class="card--max card__text grid__container">
        <form method="post" action="{{ route('comment.edit.post', ['comment_id' => $comment->id]) }}"
            class="grid__container card--m">
            @csrf
            <div class="grid__content grid__content--fixed card--m">
                <div class="card--min">
                    <label class="form-label" for="comment">Comment</label>
                </div>
                <textarea id="comment" name="comment" class="">{{ old('comment', $comment->comment) }}</textarea>
            </div>
            <input type="submit" value="Update" class="btn btn-primary">
        </form>
    </div>

    {{-- delete comment form --}}
    <div class="card--max card__text grid__container">
        <form method="post" action="{{ route('comment.delete.post') }}">
            @csrf
            <input type="hidden" name="comment_id" value="{{ $comment->id }}">
            <input type="submit" value="Delete" class="btn btn-danger">
        </form>
    </div>
@endsection
