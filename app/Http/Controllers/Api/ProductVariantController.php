<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\ProductVariant;
use App\Http\Resources\ProductVariantResource;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ProductVariantController extends Controller
{
    public function index(Request $request)
    {
        $query = ProductVariant::with(['product']);

        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        if ($request->filled('stock')) {
            $query->where('stock_quantity', '>=', $request->stock);
        }

        if ($request->filled(['start_date', 'end_date'])) {
            $query->whereBetween('created_at', [
                Carbon::parse($request->start_date)->startOfDay(),
                Carbon::parse($request->end_date)->endOfDay(),
            ]);
        }

        $variants = $query->orderBy('created_at', 'desc')->get();

        return response()->json($query->get());
    }

    public function show($id)
    {
        $variant = ProductVariant::with(['product', 'images', 'mainImage'])->findOrFail($id);
        return new ProductVariantResource($variant);
    }

    public function store(Request $request)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return response()->json(['error' => 'Bạn không có quyền thêm biến thể sản phẩm'], 403);
        }

        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'size_id' => 'nullable|exists:sizes,id',
            'color_id' => 'nullable|exists:colors,id',
            'price' => 'required|numeric|min:0',
            'discount_percent' => 'nullable|integer|min:0|max:100',
            'stock_quantity' => 'required|integer|min:0',
            'sold' => 'nullable|integer|min:0',
        ]);

        // Check trùng biến thể
        $exists = ProductVariant::where([
            'product_id' => $validated['product_id'],
            'size_id' => $validated['size_id'],
            'color_id' => $validated['color_id'],
        ])->exists();

        if ($exists) {
            return response()->json(['error' => 'Biến thể sản phẩm này đã tồn tại.'], 400);
        }

        // Tính discounted_price nếu có discount_percent
        $discountPercent = $validated['discount_percent'] ?? 0;
        $discountedPrice = $discountPercent > 0
            ? round($validated['price'] * (1 - $discountPercent / 100), 2)
            : $validated['price'];

        $variant = ProductVariant::create([
            ...$validated,
            'discounted_price' => $discountedPrice,
        ]);

        return new ProductVariantResource($variant);
    }

    public function update(Request $request, $id)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return response()->json(['error' => 'Bạn không có quyền cập nhật biến thể sản phẩm'], 403);
        }

        $variant = ProductVariant::findOrFail($id);

        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'size_id' => 'nullable|exists:sizes,id',
            'color_id' => 'nullable|exists:colors,id',
            'price' => 'required|numeric|min:0',
            'discount_percent' => 'nullable|integer|min:0|max:100',
            'stock_quantity' => 'required|integer|min:0',
            'sold' => 'nullable|integer|min:0',
        ]);

        // Kiểm tra trùng biến thể khác
        $existing = ProductVariant::where([
            'product_id' => $validated['product_id'],
            'size_id' => $validated['size_id'],
            'color_id' => $validated['color_id'],
        ])->where('id', '!=', $id)->exists();

        if ($existing) {
            return response()->json(['error' => 'Biến thể sản phẩm này đã tồn tại.'], 400);
        }

        // Cập nhật giá sau giảm
        $discountPercent = $validated['discount_percent'] ?? 0;
        $discountedPrice = $discountPercent > 0
            ? round($validated['price'] * (1 - $discountPercent / 100), 2)
            : $validated['price'];

        $variant->update([
            ...$validated,
            'discounted_price' => $discountedPrice,
        ]);

        return new ProductVariantResource($variant);
    }

    public function destroy($id)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return response()->json(['error' => 'Bạn không có quyền xóa biến thể này'], 403);
        }

        $variant = ProductVariant::findOrFail($id);
        $variant->delete();

        return response()->json(['message' => 'Biến thể sản phẩm đã được xóa'], 200);
    }
}
