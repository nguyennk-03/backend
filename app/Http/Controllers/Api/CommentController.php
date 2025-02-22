<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index()
    {
        return response()->json(Comment::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'content' => 'required|string',
        ]);

        return response()->json(Comment::create($request->all()), 201);
    }

    public function show($id)
    {
        return response()->json(Comment::findOrFail($id));
    }

    public function update(Request $request, Comment $comment)
    {
        $request->validate(['content' => 'required|string']);
        $comment->update($request->all());
        return response()->json($comment);
    }

    public function destroy(Comment $comment)
    {
        $comment->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }
}
