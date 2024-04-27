@extends('layouts/main')

@section('title', 'Event')

@section('main')
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

    <div class="card--max card__text grid__container">
        {{-- delete comment --}}
        <form method="post" action="{{ route('comment.delete.post') }}">
            @csrf
            <input type="hidden" name="comment_id" value="{{ $comment->id }}">
            <input type="submit" value="Delete" class="btn btn-danger">
        </form>
    </div>



    @if ($errors->any())
        <div class="grid__container card--m errors">
            <div class="card--full">
                @foreach ($errors->all() as $error)
                    <p class="alert">
                        {{ $error }}
                    </p>
                @endforeach
            </div>
        </div>
    @endif
@endsection
