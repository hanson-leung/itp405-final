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

        return redirect()->route('event', ['id' => $request->input('event_id')]);
    }

    public function commentDeleteRequest($id)
    {
        $comment = Comment::find($id);
        $comment->delete();

        return redirect()->route('event', ['id' => $comment->event_id]);
    }

    public function commentEditRequest(Request $request)
    {
        $comment = Comment::find($request->input('id'));
        $comment->comment = $request->input('comment');
        $comment->save();

        return redirect()->route('event', ['id' => $comment->event_id]);
    }
}
