<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function index()
    {
        return Comment::with('user:id,name')->orderBy('created_at', 'desc')->get();
    }

    public function show($id)
    {
        return Comment::with('user:id,name')->findOrFail($id);
    }

    public function store(Request $request)
    {
        $request->validate([
            'variant_id' => 'required|exists:products,id',
            'message' => 'required|string',
        ]);

        $comment = Comment::create([
            'variant_id' => $request->variant_id,
            'user_id' => Auth::id(),
            'message' => $request->message,
            'is_staff' => Auth::user()->role === 'admin' ? true : false,
        ]);

        return response()->json($comment, 201);
    }

    public function update(Request $request, $id)
    {
        $comment = Comment::findOrFail($id);

        if ($comment->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate(['message' => 'required|string']);
        $comment->update(['message' => $request->message]);

        return response()->json($comment);
    }

    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);

        if ($comment->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $comment->delete();
        return response()->json(['message' => 'Comment deleted successfully']);
    }
}
