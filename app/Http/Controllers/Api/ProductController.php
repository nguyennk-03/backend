<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Carbon\Carbon;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        // Tìm kiếm theo tên sản phẩm
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        // Lọc theo danh mục sản phẩm
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Lọc theo thương hiệu sản phẩm
        if ($request->filled('brand_id')) {
            $query->where('brand_id', $request->brand_id);
        }

        // Lọc theo trạng thái sản phẩm
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Lọc theo sản phẩm đang giảm giá
        if ($request->filled('sale')) {
            $query->where('sale', $request->sale);
        }

        // Lọc theo sản phẩm hot
        if ($request->filled('hot')) {
            $query->where('hot', $request->hot);
        }

        // Lọc theo ngày tạo sản phẩm
        if ($request->filled(['start_date', 'end_date'])) {
            $startDate = Carbon::parse($request->start_date)->startOfDay();
            $endDate = Carbon::parse($request->end_date)->endOfDay();
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        // Mặc định sắp xếp theo ngày tạo mới nhất
        $query->orderBy('created_at', 'desc');

        return response()->json($query->get());
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        return response()->json($product);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',  // Giá sản phẩm phải là số dương
            'image' => 'nullable|string', // Hình ảnh sản phẩm có thể null
            'size' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'sale' => 'nullable|in:0,1',  // 0: Không giảm giá, 1: Đang giảm giá
            'hot' => 'nullable|in:0,1,2,3', // 0: Thường, 1: Mới, 2: Nổi bật, 3: Bán chạy
            'status' => 'nullable|in:0,1',  // 0: Ẩn, 1: Hiển thị
            'stock_quantity' => 'required|integer|min:0',  // Số lượng tồn kho phải là số nguyên và >= 0
            'sold' => 'nullable|integer|min:0',  // Số lượng đã bán, có thể null
            'category_id' => 'nullable|exists:categories,id',  // Danh mục sản phẩm phải tồn tại trong bảng categories
            'brand_id' => 'nullable|exists:brands,id',  // Thương hiệu sản phẩm phải tồn tại trong bảng brands
        ]);

        // Tạo sản phẩm mới
        $product = Product::create($validated);

        return response()->json($product, 201);
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|string',
            'size' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'sale' => 'nullable|in:0,1',
            'hot' => 'nullable|in:0,1,2,3',
            'status' => 'nullable|in:0,1',
            'stock_quantity' => 'required|integer|min:0',
            'sold' => 'nullable|integer|min:0',
            'category_id' => 'nullable|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
        ]);

        // Cập nhật sản phẩm
        $product->update($validated);

        return response()->json($product);
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return response()->json(null, 204);
    }
}
