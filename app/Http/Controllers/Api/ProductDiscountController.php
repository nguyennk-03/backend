<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProductDiscount;
use Illuminate\Http\Request;

class ProductDiscountController extends Controller
{
    // Lấy tất cả các chương trình giảm giá hoặc lọc theo product_id
    public function index(Request $request)
    {
        $query = ProductDiscount::with('product');

        if ($request->has('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        return response()->json($query->get());
    }

    // Lấy chi tiết một chương trình giảm giá
    public function show($id)
    {
        $discount = ProductDiscount::with('product')->findOrFail($id);
        return response()->json($discount);
    }

    // Tạo mới một chương trình giảm giá
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'discount_percentage' => 'required|numeric|min:0|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        $discount = ProductDiscount::create($validated);
        return response()->json($discount, 201);
    }

    // Cập nhật một chương trình giảm giá
    public function update(Request $request, $id)
    {
        $discount = ProductDiscount::findOrFail($id);
        $validated = $request->validate([
            'discount_percentage' => 'numeric|min:0|max:100',
            'start_date' => 'date',
            'end_date' => 'date|after:start_date',
        ]);

        $discount->update($validated);
        return response()->json($discount);
    }

    // Xóa một chương trình giảm giá
    public function destroy($id)
    {
        $discount = ProductDiscount::findOrFail($id);
        $discount->delete();
        return response()->json(null, 204);
    }
}
