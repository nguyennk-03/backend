<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class ReviewController extends Controller
{
    // Hiển thị danh sách đánh giá
    public function index()
    {
        $reviews = Review::with('user', 'product')->get();
        $products = Product::all();
        return view('admin.reviews.index', compact('reviews','products'));
    }

    // Lưu review mới
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        Review::create([
            'user_id' => Auth::id(),
            'product_id' => $request->product_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return redirect()->route('reviews')->with('success', 'Đánh giá đã được thêm!');
    }

    // Hiển thị form chỉnh sửa review
    public function edit($id)
    {
        $review = Review::findOrFail($id);
        return view('admin.reviews.edit', compact('review'));
    }

    // Cập nhật review
    public function update(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        $review = Review::findOrFail($id);
        $review->update([
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return redirect()->route('reviews')->with('success', 'Đánh giá đã được cập nhật!');
    }

    // Xóa review
    public function destroy($id)
    {
        Review::findOrFail($id)->delete();
        return redirect()->route('reviews')->with('success', 'Đánh giá đã được xóa!');
    }
}
