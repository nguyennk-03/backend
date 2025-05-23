<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Review;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Enums\OrderStatusEnum;
use Carbon\Carbon;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = Review::with('user');

        if ($request->has('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        if ($request->has('rating')) {
            $query->where('rating', $request->rating);
        }

        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->has(['start_date', 'end_date'])) {
            $startDate = Carbon::parse($request->start_date)->startOfDay();
            $endDate = Carbon::parse($request->end_date)->endOfDay();
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        return response()->json($query->orderBy('created_at', 'desc')->get());
    }

    public function show($id)
    {
        $review = Review::with('user')->findOrFail($id);
        return response()->json($review);
    }

    public function store(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Bạn cần đăng nhập để đánh giá sản phẩm'], 401);
        }

        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        // Kiểm tra người dùng đã nhận hàng sản phẩm này chưa
        $hasDeliveredOrder = Order::where('user_id', Auth::id())
            ->where('status', OrderStatusEnum::DELIVERED)
            ->whereHas('orderItems', function ($query) use ($validated) {
                $query->where('product_id', $validated['product_id']);
            })
            ->exists();

        if (!$hasDeliveredOrder) {
            return response()->json(['error' => 'Bạn chỉ có thể đánh giá sau khi đã nhận hàng sản phẩm.'], 403);
        }

        // Kiểm tra đã đánh giá trước đó chưa
        if (Review::where('product_id', $validated['product_id'])
            ->where('user_id', Auth::id())
            ->exists()
        ) {
            return response()->json(['error' => 'Bạn đã đánh giá sản phẩm này rồi.'], 400);
        }

        // Tạo đánh giá
        $review = Review::create([
            'user_id' => Auth::id(),
            'product_id' => $validated['product_id'],
            'rating' => $validated['rating'],
            'comment' => $validated['comment'] ?? null,
        ]);

        return response()->json($review, 201);
    }

    public function update(Request $request, $id)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Bạn cần đăng nhập để cập nhật đánh giá'], 401);
        }

        $review = Review::findOrFail($id);

        if ($review->user_id !== Auth::id()) {
            return response()->json(['error' => 'Bạn không có quyền chỉnh sửa đánh giá này'], 403);
        }

        $validated = $request->validate([
            'rating' => 'integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        $review->update($validated);
        return response()->json($review);
    }

    public function destroy($id)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Bạn cần đăng nhập để xóa đánh giá'], 401);
        }

        $review = Review::findOrFail($id);

        if ($review->user_id !== Auth::id()) {
            return response()->json(['error' => 'Bạn không có quyền xóa đánh giá này'], 403);
        }

        $review->delete();
        return response()->json(['message' => 'Đánh giá đã được xóa.'], 200);
    }
}
