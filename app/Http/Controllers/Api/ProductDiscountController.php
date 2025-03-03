<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\ProductDiscount;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ProductDiscountController extends Controller
{
    public function index(Request $request)
    {
        $query = ProductDiscount::with('product');

        if ($request->has('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        if ($request->has('active') && $request->active == 'true') {
            $query->whereDate('start_date', '<=', now())
                ->whereDate('end_date', '>=', now());
        }

        return response()->json($query->orderBy('start_date', 'desc')->get());
    }

    public function show($id)
    {
        $discount = ProductDiscount::with('product')->findOrFail($id);
        return response()->json($discount);
    }

    public function store(Request $request)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return response()->json(['error' => 'Bạn không có quyền tạo chương trình giảm giá.'], 403);
        }

        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'discount_percentage' => 'required|numeric|min:0|max:100',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
        ]);

        $overlappingDiscount = ProductDiscount::where('product_id', $validated['product_id'])
            ->where(function ($query) use ($validated) {
                $query->whereBetween('start_date', [$validated['start_date'], $validated['end_date']])
                    ->orWhereBetween('end_date', [$validated['start_date'], $validated['end_date']])
                    ->orWhere(function ($query) use ($validated) {
                        $query->where('start_date', '<=', $validated['start_date'])
                            ->where('end_date', '>=', $validated['end_date']);
                    });
            })
            ->exists();

        if ($overlappingDiscount) {
            return response()->json(['error' => 'Sản phẩm này đã có chương trình giảm giá trong thời gian này.'], 400);
        }

        $discount = ProductDiscount::create($validated);
        return response()->json($discount, 201);
    }

    public function update(Request $request, $id)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return response()->json(['error' => 'Bạn không có quyền cập nhật chương trình giảm giá.'], 403);
        }

        $discount = ProductDiscount::findOrFail($id);
        $validated = $request->validate([
            'discount_percentage' => 'numeric|min:0|max:100',
            'start_date' => 'date|after_or_equal:today',
            'end_date' => 'date|after:start_date',
        ]);

        $discount->update($validated);
        return response()->json($discount);
    }

    public function destroy($id)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return response()->json(['error' => 'Bạn không có quyền xóa chương trình giảm giá này.'], 403);
        }

        $discount = ProductDiscount::findOrFail($id);
        $discount->delete();

        return response()->json(['message' => 'Chương trình giảm giá đã được xóa.'], 200);
    }
}
