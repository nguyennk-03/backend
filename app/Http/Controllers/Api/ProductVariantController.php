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
        $query = ProductVariant::with(['product', 'size', 'color', 'images', 'mainImage']);

        if ($request->has('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        if ($request->has('size_id')) {
            $query->where('size_id', $request->size_id);
        }

        if ($request->has('color_id')) {
            $query->where('color_id', $request->color_id);
        }

        if ($request->has('stock')) {
            $query->where('stock_quantity', '>=', $request->stock);
        }

        if ($request->has(['start_date', 'end_date'])) {
            $query->whereBetween('created_at', [
                Carbon::parse($request->start_date)->startOfDay(),
                Carbon::parse($request->end_date)->endOfDay(),
            ]);
        }

        $variants = $query->orderBy('created_at', 'desc')->get();

        return ProductVariantResource::collection($variants);
    }

    public function show($id)
    {
        $variant = ProductVariant::with(['product', 'size', 'color'])->findOrFail($id);
        return response()->json($variant);
    }

    public function store(Request $request)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return response()->json(['error' => 'Bạn không có quyền thêm biến thể sản phẩm'], 403);
        }

        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'size_id' => 'required|exists:sizes,id',
            'color_id' => 'required|exists:colors,id',
            'stock' => 'required|integer|min:0',
        ]);

        if (
            ProductVariant::where([
                'product_id' => $validated['product_id'],
                'size_id' => $validated['size_id'],
                'color_id' => $validated['color_id'],
            ])->exists()
        ) {
            return response()->json(['error' => 'Biến thể sản phẩm này đã tồn tại.'], 400);
        }

        $variant = ProductVariant::create($validated);
        return response()->json($variant, 201);
    }

    public function update(Request $request, $id)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return response()->json(['error' => 'Bạn không có quyền cập nhật biến thể sản phẩm'], 403);
        }

        $variant = ProductVariant::findOrFail($id);
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'size_id' => 'required|exists:sizes,id',
            'color_id' => 'required|exists:colors,id',
            'stock' => 'required|integer|min:0',
        ]);

        $existingVariant = ProductVariant::where([
            'product_id' => $validated['product_id'],
            'size_id' => $validated['size_id'],
            'color_id' => $validated['color_id'],
        ])->where('id', '!=', $id)->first();

        if ($existingVariant) {
            return response()->json(['error' => 'Biến thể sản phẩm này đã tồn tại.'], 400);
        }

        $variant->update($validated);
        return response()->json($variant);
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
