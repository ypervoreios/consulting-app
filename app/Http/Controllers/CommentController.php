<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{

public function store(Request $request, Task $task)
{
    //$this->authorize('createComment', $task);

    $request->validate([
        'content' => 'required|string|max:500',
    ]);

    Comment::create([
        'task_id' => $task->id,
        'user_id' => Auth::id(),
        'content' => $request->content,
    ]);

    return back()->with('success', 'Το σχόλιο δημιουργήθηκε με επιτυχία!');
}

    public function update(Request $request, Comment $comment)
    {
        if ($comment->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'content' => 'required|string|max:500',
        ]);

        $comment->update([
            'content' => $request->content,
        ]);

        return back()->with('success', 'Το σχόλιο ενημερώθηκε.');
    }

    public function destroy(Comment $comment)
    {
        if ($comment->user_id !== Auth::id()) {
            abort(403);
        }

        $comment->delete();

        return back()->with('success', 'Το σχόλιο διαγράφηκε.');
    }
}
