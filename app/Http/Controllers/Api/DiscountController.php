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

        // Lọc theo mã giảm giá (nếu có)
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

        // Kiểm tra hiệu lực mã giảm giá
        if (now() < $discount->start_date || now() > $discount->end_date) {
            return response()->json(['error' => 'Mã giảm giá không còn hiệu lực'], 400);
        }

        // Kiểm tra số lần sử dụng
        if ($discount->max_uses !== null && $discount->max_uses <= 0) {
            return response()->json(['error' => 'Mã giảm giá đã hết lượt sử dụng'], 400);
        }

        // Tính giảm giá
        $discountAmount = ($discount->discount_type === 'percentage')
            ? ($discount->value / 100) * $validated['total_price']
            : $discount->value;

        $newTotalPrice = max(0, $validated['total_price'] - $discountAmount);

        // Giảm số lượt sử dụng
        if ($discount->max_uses !== null) {
            $discount->decrement('max_uses');
        }

        return response()->json([
            'discount_amount' => $discountAmount,
            'new_total_price' => $newTotalPrice,
            'discount_code' => $discount->code,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:discounts,code',
            'discount_type' => 'required|in:fixed,percentage',
            'value' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'max_uses' => 'nullable|integer|min:1',
        ]);

        return response()->json(Discount::create($validated), 201);
    }

    public function update(Request $request, $id)
    {
        $discount = Discount::findOrFail($id);

        $validated = $request->validate([
            'code' => 'required|string|unique:discounts,code,' . $id,
            'discount_type' => 'required|in:fixed,percentage',
            'value' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'max_uses' => 'nullable|integer|min:1',
        ]);

        $discount->update($validated);
        return response()->json($discount);
    }

    public function destroy($id)
    {
        $discount = Discount::findOrFail($id);

        // Kiểm tra nếu mã đã được sử dụng trong đơn hàng
        if ($discount->orders()->exists()) {
            return response()->json(['error' => 'Không thể xóa mã giảm giá vì đã được sử dụng'], 400);
        }

        $discount->delete();
        return response()->json(['message' => 'Mã giảm giá đã được xóa'], 204);
    }
}
