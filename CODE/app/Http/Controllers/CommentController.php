<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;


class CommentController extends Controller
{
    public function store(Post $post)
    {
        $attribute = request()->validate([
            'comment' => 'required|string|max:255',
        ]);

        Comment::create([
            'user_id' => Auth::user()->id,
            'post_id' => $post->id,
            'content' => $attribute['comment'],
        ]);

        return redirect()->back();
    }

    public function edit(Comment $comment)
    {
        if ($comment->user_id !== Auth::user()->id) {
            abort(403);
        }

        return view('edit-comment', compact('comment'));
    }

    public function update(Request $request, Comment $comment)
    {
        if ($comment->user_id !== Auth::user()->id) {
            abort(403);
        }

        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $comment->update([
            'content' => $request->input('content'),
        ]);

        return redirect()->route('posts.show', $comment->post_id)->with('success', 'Comment updated.');
    }

    public function destroy(Comment $comment)
    {
        if ($comment->user_id !== Auth::user()->id) {
            abort(403); // Unauthorized
        }

        $comment->delete();

        return redirect()->back()->with('success', 'Comment deleted.');
    }

}
