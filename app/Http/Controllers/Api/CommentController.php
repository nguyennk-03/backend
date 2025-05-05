<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    // Lấy tất cả bình luận theo sản phẩm, dạng cây
    public function index(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $comments = Comment::with(['user', 'replies.user'])
            ->where('product_id', $request->product_id)
            ->whereNull('parent_id') // chỉ lấy bình luận gốc
            ->where('is_hidden', 0)
            ->latest()
            ->get();

        return response()->json($comments);
    }

    // Tạo mới bình luận hoặc trả lời
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'message' => 'required|string',
            'parent_id' => 'nullable|exists:comments,id',
        ]);

        $comment = Comment::create([
            'product_id' => $request->product_id,
            'user_id' => Auth::id(),
            'message' => $request->message,
            'is_staff' => Auth::user()?->role === 'admin',
            'parent_id' => $request->parent_id,
        ]);

        return response()->json($comment->load('user', 'parent'), 201);
    }

    // Xóa bình luận (chỉ nếu là chủ sở hữu hoặc admin)
    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);

        if (Auth::id() !== $comment->user_id && Auth::user()?->role !== 'admin') {
            return response()->json(['error' => 'Không có quyền xóa bình luận này'], 403);
        }

        $comment->delete();

        return response()->json(['message' => 'Đã xóa bình luận'], 200);
    }

    // Admin: Ẩn hoặc hiện bình luận
    public function toggleVisibility($id)
    {
        $comment = Comment::findOrFail($id);

        if (Auth::user()?->role !== 'admin') {
            return response()->json(['error' => 'Chỉ admin mới được phép ẩn/hiện bình luận'], 403);
        }

        $comment->is_hidden = !$comment->is_hidden;
        $comment->save();

        return response()->json(['message' => 'Đã cập nhật trạng thái hiển thị', 'is_hidden' => $comment->is_hidden]);
    }
}
