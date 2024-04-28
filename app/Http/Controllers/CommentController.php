<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use Auth;

class CommentController extends Controller
{
    // comment create request
    public function commentRequest(Request $request)
    {
        // validate
        $request->validate([
            'comment' => 'required|max:255',
        ]);

        // create comment
        try {
            $comment = new Comment();
            $comment->user_id = Auth::id();
            $comment->event_id = $request->input('event_id');
            $comment->comment = $request->input('comment');
            $comment->save();

            // redirect to event 
            return redirect()->route('event', ['event_id' => $request->input('event_id')]);
        } catch (\Exception $e) {
            return redirect()->route('event', ['event_id' => $request->input('event_id')])->with('message', 'Error creating comment');
        }
    }

    // comment delete request
    public function commentDeleteRequest(Request $request)
    {
        // check if user is authorized
        if (Auth::user()->cannot('delete', Comment::find($request->input('comment_id')))) {
            abort(403, 'Unauthorized action.');
        }

        // delete comment
        try {
            $event_id = Comment::find($request->input('comment_id'))->event_id;
            $comment = Comment::find($request->input('comment_id'));
            $comment->delete();

            // redirect to event
            return redirect()->route('event', ['event_id' => $event_id])->with('message', 'Comment deleted!');;
        } catch (\Exception $e) {
            return redirect()->route('event', ['event_id' => $event_id])->with('message', 'Error deleting comment');
        }
    }

    // comment edit page
    public function commentEdit($comment_id)
    {
        // check if user is authorized
        if (Auth::user()->cannot('edit', Comment::find($comment_id))) {
            abort(403, 'Unauthorized action.');
        }

        // return view
        return view('events.comments.comment_edit', [
            'comment' => Comment::find($comment_id),
        ]);
    }

    // comment edit request
    public function commentEditRequest(Request $request, $comment_id)
    {
        // check if user is authorized
        if (Auth::user()->cannot('edit', Comment::find($comment_id))) {
            abort(403, 'Unauthorized action.');
        }

        // validate request
        $request->validate([
            'comment' => 'required|max:255',
        ]);

        // update comment
        try {
            $comment = Comment::find($comment_id);
            $comment->comment = $request->input('comment');
            $comment->save();

            // redirect to event
            return redirect()->route('event', ['event_id' => $comment->event_id])->with('message', 'Comment updated');;
        } catch (\Exception $e) {
            return redirect()->route('event', ['event_id' => $comment->event_id])->with('message', 'Error updating comment');
        }
    }
}
