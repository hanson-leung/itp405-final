<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use Auth;

class CommentController extends Controller
{
    public function commentRequest(Request $request)
    {
        $request->validate([
            'comment' => 'required|max:255',
        ]);

        $comment = new Comment();
        $comment->user_id = Auth::id();
        $comment->event_id = $request->input('event_id');
        $comment->comment = $request->input('comment');
        $comment->save();

        return redirect()->route('event', ['event_id' => $request->input('event_id')]);
    }

    public function commentDeleteRequest(Request $request)
    {
        if (Auth::user()->cannot('delete', Comment::find($request->input('comment_id')))) {
            abort(403, 'Unauthorized action.');
        }

        $event_id = Comment::find($request->input('comment_id'))->event_id;
        $comment = Comment::find($request->input('comment_id'));
        $comment->delete();

        return redirect()->route('event', ['event_id' => $event_id])->with('message', 'Comment deleted');;
    }

    public function commentEdit($comment_id)
    {

        if (Auth::user()->cannot('edit', Comment::find($comment_id))) {
            abort(403, 'Unauthorized action.');
        }

        return view('events.comments.comment_edit', [
            'comment' => Comment::find($comment_id),
        ]);
    }

    public function commentEditRequest(Request $request, $comment_id)
    {

        if (Auth::user()->cannot('edit', Comment::find($comment_id))) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'comment' => 'required|max:255',
        ]);

        $comment = Comment::find($comment_id);
        $comment->comment = $request->input('comment');
        $comment->save();

        return redirect()->route('event', ['event_id' => $comment->event_id])->with('message', 'Comment updated');;
    }
}
