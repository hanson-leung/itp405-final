<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use Auth;

class CommentController extends Controller
{
    public function commentRequest(Request $request)
    {
        $comment = new Comment();
        $comment->user_id = Auth::id();
        $comment->event_id = $request->input('event_id');
        $comment->comment = $request->input('comment');
        $comment->save();

        return redirect()->route('event', ['event_id' => $request->input('event_id')]);
    }

    public function commentDeleteRequest(
        $event_id,
        $comment_id
    ) {
        $comment = Comment::find($comment_id);
        $comment->delete();

        return redirect()->route('event', ['event_id' => $event_id]);
    }

    public function commentEditRequest(Request $request)
    {
        $comment = Comment::find($request->input('event_id'));
        $comment->comment = $request->input('comment');
        $comment->save();

        return redirect()->route('event', ['event_id' => $comment->event_id]);
    }
}
