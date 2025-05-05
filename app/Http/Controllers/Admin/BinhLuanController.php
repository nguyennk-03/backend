<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BinhLuanController extends Controller
{
    /**
     * Display a listing of the comments.
     */
    public function index(Request $request)
    {
        $query = Comment::with(['user', 'product', 'parent', 'replies']); // Load các quan hệ

        // Filter by product
        if ($request->product_id) {
            $query->where('product_id', $request->product_id);
        }

        // Filter by visibility
        if ($request->has('is_hidden')) {
            $query->where('is_hidden', $request->is_hidden);
        }

        // Search by message content
        if ($request->search) {
            $query->where('message', 'like', '%' . $request->search . '%');
        }

        $comments = $query->orderBy('created_at', 'desc')->get();
        $products = Product::all();

        return view('admin.comments.index', compact('comments', 'products'));
    }

    public function show($id)
    {
        $comment = Comment::with(['user', 'product', 'parent'])->findOrFail($id);
        $products = Product::all(); // Lấy danh sách sản phẩm để hiển thị trong dropdown
        $comments = Comment::with(['user', 'product', 'parent', 'replies'])->orderBy('created_at', 'desc')->get();
        return view('admin.comments.index', compact('comment', 'products', 'comments'));
    }

    /**
     * Update the specified comment.
     */
    public function update($id)
    {
        // Tìm bình luận theo ID
        $comment = Comment::findOrFail($id);

        // Đảo ngược trạng thái hiển thị
        $comment->is_hidden = !$comment->is_hidden;
        $comment->save();

        // Trả về thông báo thành công và chuyển hướng về trang danh sách bình luận
        return redirect()->route('binh-luan.index')->with('success', 'Cập nhật trạng thái hiển thị bình luận thành công.');
    }

    /**
     * Remove the specified comment.
     */
    public function destroy(Comment $binhLuan)
    {
        try {
            $binhLuan->delete();
            return redirect()->route('binh-luan.index')->with('success', 'Bình luận đã được xóa!');
        } catch (\Exception $e) {
            return back()->with('error', 'Đã có lỗi xảy ra: ' . $e->getMessage());
        }
    }
}
