<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\Size;
use Illuminate\Http\Request;

class SanPhamController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query()->with([
            'category',
            'brand',
            'variant' => function ($q) {
                $q->with('images'); // Load images cho mỗi variant
            }
        ]);

        // Lọc theo danh mục
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Lọc theo thương hiệu
        if ($request->filled('brand_id')) {
            $query->where('brand_id', $request->brand_id);
        }

        // Lọc theo khoảng giá
        if ($request->filled('min_price') || $request->filled('max_price')) {
            $query->whereHas('variant', function ($q) use ($request) {
                if ($request->filled('min_price')) {
                    $q->where('price', '>=', $request->min_price);
                }
                if ($request->filled('max_price')) {
                    $q->where('price', '<=', $request->max_price);
                }
            });
        }

        // Sắp xếp
        if ($request->filled('sort_by')) {
            switch ($request->sort_by) {
                case 'price_asc':
                    $query->leftJoin('product_variant', 'products.id', '=', 'product_variant.product_id')
                        ->select('products.*')
                        ->orderByRaw('MIN(product_variant.price) ASC')
                        ->groupBy('products.id');
                    break;
                case 'price_desc':
                    $query->leftJoin('product_variant', 'products.id', '=', 'product_variant.product_id')
                        ->select('products.*')
                        ->orderByRaw('MAX(product_variant.price) DESC')
                        ->groupBy('products.id');
                    break;
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'oldest':
                    $query->orderBy('created_at', 'asc');
                    break;
            }
        }

        // Lấy tất cả sản phẩm (không phân trang)
        $products = $query->get();
        $categories = Category::where('status', 1)->get();
        $brands = Brand::where('status', 1)->get();
        $sizes = Size::where('status', 1)->get();
        $colors = Color::where('status', 1)->get();

        return view('admin.products.index', compact('products', 'categories', 'brands', 'sizes', 'colors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'variant.0.price' => 'required|numeric|min:0',
            'variant.0.stock_quantity' => 'required|integer|min:0',
            'variant.0.size_id' => 'nullable|exists:sizes,id',
            'variant.0.color_id' => 'nullable|exists:colors,id',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $product = Product::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'category_id' => $validated['category_id'],
            'brand_id' => $validated['brand_id'],
            'stock_quantity' => $validated['variant'][0]['stock_quantity'],
        ]);

        $variant = $product->variant()->create([
            'price' => $validated['variant'][0]['price'],
            'stock_quantity' => $validated['variant'][0]['stock_quantity'],
            'size_id' => $validated['variant'][0]['size_id'],
            'color_id' => $validated['variant'][0]['color_id'],
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('images', 'public');
                $variant->images()->create([
                    'path' => $path,
                    'is_main' => true, // Giả định ảnh đầu tiên là ảnh chính
                ]);
            }
        }

        return redirect()->route('products.index')->with('success', 'Thêm sản phẩm thành công!');
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'variant.0.price' => 'required|numeric|min:0',
            'variant.0.stock_quantity' => 'required|integer|min:0',
            'variant.0.size_id' => 'nullable|exists:sizes,id',
            'variant.0.color_id' => 'nullable|exists:colors,id',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $product->update([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'category_id' => $validated['category_id'],
            'brand_id' => $validated['brand_id'],
            'stock_quantity' => $validated['variant'][0]['stock_quantity'],
        ]);

        $variant = $product->variant()->first();
        if ($variant) {
            $variant->update([
                'price' => $validated['variant'][0]['price'],
                'stock_quantity' => $validated['variant'][0]['stock_quantity'],
                'size_id' => $validated['variant'][0]['size_id'],
                'color_id' => $validated['variant'][0]['color_id'],
            ]);
        }

        if ($request->hasFile('images')) {
            $variant->images()->delete(); // Xóa ảnh cũ
            foreach ($request->file('images') as $image) {
                $path = $image->store('images', 'public');
                $variant->images()->create([
                    'path' => $path,
                    'is_main' => true,
                ]);
            }
        }

        return redirect()->route('products.index')->with('success', 'Cập nhật sản phẩm thành công!');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Xóa sản phẩm thành công!');
    }
}
