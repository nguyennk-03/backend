<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    // Lấy tất cả các đánh giá hoặc lọc theo product_id
    public function index(Request $request)
    {
        $query = Review::with('user');

        if ($request->has('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        return response()->json($query->get());
    }

    // Lấy chi tiết một đánh giá
    public function show($id)
    {
        $review = Review::with('user')->findOrFail($id);
        return response()->json($review);
    }

    // Tạo mới một đánh giá
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'user_id' => 'required|exists:users,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        $review = Review::create($validated);
        return response()->json($review, 201);
    }

    // Cập nhật đánh giá
    public function update(Request $request, $id)
    {
        $review = Review::findOrFail($id);
        $validated = $request->validate([
            'rating' => 'integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        $review->update($validated);
        return response()->json($review);
    }

    // Xóa đánh giá
    public function destroy($id)
    {
        $review = Review::findOrFail($id);
        $review->delete();
        return response()->json(null, 204);
    }
}
