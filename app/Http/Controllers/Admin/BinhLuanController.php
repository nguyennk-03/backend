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
        $query = Comment::with(['user', 'product', 'parent']);

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

    /**
     * Show the form for creating a new comment.
     */
    public function create()
    {
        $users = User::all();
        $products = Product::all();
        $comments = Comment::whereNull('parent_id')->get(); // For parent comment selection
        return view('admin.comments.create', compact('users', 'products', 'comments'));
    }

    /**
     * Store a newly created comment.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'user_id' => 'nullable|exists:users,id',
                'product_id' => 'nullable|exists:products,id',
                'message' => 'required|string',
                'is_staff' => 'required|boolean',
                'parent_id' => 'nullable|exists:comments,id',
                'is_hidden' => 'required|in:0,1',
            ]);

            $data = $request->only('user_id', 'product_id', 'message', 'is_staff', 'parent_id', 'is_hidden');

            // If user_id is not provided, use the authenticated user
            if (!$data['user_id'] && Auth::check()) {
                $data['user_id'] = Auth::id();
            }

            Comment::create($data);

            return redirect()->route('binh-luan.index')->with('success', 'Bình luận đã được thêm!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Đã có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified comment.
     */
    public function show(Comment $binhLuan)
    {
        $binhLuan->load(['user', 'product', 'parent', 'children']);
        return view('admin.comments.show', compact('binhLuan'));
    }

    /**
     * Show the form for editing a comment.
     */
    public function edit(Comment $binhLuan)
    {
        $users = User::all();
        $products = Product::all();
        $comments = Comment::whereNull('parent_id')->where('id', '!=', $binhLuan->id)->get();
        return view('admin.comments.edit', compact('binhLuan', 'users', 'products', 'comments'));
    }

    /**
     * Update the specified comment.
     */
    public function update(Request $request, Comment $binhLuan)
    {
        try {
            $validated = $request->validate([
                'user_id' => 'nullable|exists:users,id',
                'product_id' => 'nullable|exists:products,id',
                'message' => 'required|string',
                'is_staff' => 'required|boolean',
                'parent_id' => 'nullable|exists:comments,id',
                'is_hidden' => 'required|in:0,1',
            ]);

            $data = $request->only('user_id', 'product_id', 'message', 'is_staff', 'parent_id', 'is_hidden');

            $binhLuan->update($data);

            return redirect()->route('binh-luan.index')->with('success', 'Bình luận đã được cập nhật!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Đã có lỗi xảy ra: ' . $e->getMessage());
        }
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
