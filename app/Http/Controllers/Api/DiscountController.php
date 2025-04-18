<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Discount;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    public function index(Request $request)
    {
        $query = Discount::query();

        if ($request->has('code')) {
            $query->where('code', $request->code);
        }

        return response()->json($query->get());
    }

    public function applyDiscount(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|exists:discounts,code',
            'total_price' => 'required|numeric|min:0',
        ]);

        $discount = Discount::where('code', $validated['code'])->first();

        if (!$discount->is_active) {
            return response()->json(['error' => 'Mã giảm giá không còn hiệu lực'], 400);
        }

        if (now()->lt($discount->start_date) || now()->gt($discount->end_date)) {
            return response()->json(['error' => 'Mã giảm giá đã hết hạn'], 400);
        }

        if ($discount->usage_limit !== null && $discount->used_count >= $discount->usage_limit) {
            return response()->json(['error' => 'Mã giảm giá đã hết lượt sử dụng'], 400);
        }

        if ($validated['total_price'] < $discount->min_order_amount) {
            return response()->json(['error' => 'Không đạt mức tối thiểu để áp dụng mã giảm giá'], 400);
        }

        $discountAmount = $discount->discount_type === 0 // 0: percentage
            ? ($discount->value / 100) * $validated['total_price']
            : $discount->value;

        $newTotalPrice = max(0, $validated['total_price'] - $discountAmount);

        // Tăng lượt đã sử dụng
        $discount->increment('used_count');

        return response()->json([
            'discount_amount' => round($discountAmount, 2),
            'new_total_price' => round($newTotalPrice, 2),
            'discount_code' => $discount->code,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:discounts,code',
            'discount_type' => 'required|in:0,1', // 0: percentage, 1: fixed
            'value' => 'required|numeric|min:0',
            'min_order_amount' => 'nullable|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'usage_limit' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
        ]);

        $validated['used_count'] = 0;

        return response()->json(Discount::create($validated), 201);
    }

    public function update(Request $request, $id)
    {
        $discount = Discount::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:discounts,code,' . $id,
            'discount_type' => 'required|in:0,1',
            'value' => 'required|numeric|min:0',
            'min_order_amount' => 'nullable|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'usage_limit' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
        ]);

        $discount->update($validated);

        return response()->json($discount);
    }

    public function destroy($id)
    {
        $discount = Discount::findOrFail($id);

        if ($discount->used_count > 0) {
            return response()->json(['error' => 'Không thể xóa mã đã được sử dụng'], 400);
        }

        $discount->delete();
        return response()->json(['message' => 'Mã giảm giá đã được xóa'], 204);
    }
}
