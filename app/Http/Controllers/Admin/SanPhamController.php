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
            'variants' => function ($q) {
                $q->with('images');
            }
        ]);

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('brand_id')) {
            $query->where('brand_id', $request->brand_id);
        }

        if ($request->filled('min_price') || $request->filled('max_price')) {
            $query->whereHas('variants', function ($q) use ($request) {
                if ($request->filled('min_price')) {
                    $q->where('price', '>=', $request->min_price);
                }
                if ($request->filled('max_price')) {
                    $q->where('price', '<=', $request->max_price);
                }
            });
        }

        if ($request->filled('sort_by')) {
            switch ($request->sort_by) {
                case 'price_asc':
                    $query->leftJoin('product_variants', 'products.id', '=', 'product_variants.product_id')
                        ->select('products.*')
                        ->orderByRaw('MIN(product_variants.price) ASC')
                        ->groupBy('products.id');
                    break;
                case 'price_desc':
                    $query->leftJoin('product_variants', 'products.id', '=', 'product_variants.product_id')
                        ->select('products.*')
                        ->orderByRaw('MAX(product_variants.price) DESC')
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
            'sale' => 'nullable|boolean',
            'hot' => 'nullable|integer|in:0,1,2,3',
            'status' => 'nullable|boolean',
            'variants.0.price' => 'required|numeric|min:0',
            'variants.0.stock_quantity' => 'required|integer|min:0',
            'variants.0.size_id' => 'nullable|exists:sizes,id',
            'variants.0.color_id' => 'nullable|exists:colors,id',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $product = Product::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'category_id' => $validated['category_id'] ?? null,
            'brand_id' => $validated['brand_id'] ?? null,
            'sale' => $validated['sale'] ?? 0,
            'hot' => $validated['hot'] ?? 0,
            'status' => $validated['status'] ?? 1,
            'stock_quantity' => $validated['variants'][0]['stock_quantity'],
            'sold' => 0,
        ]);

        $variant = $product->variants()->create([
            'price' => $validated['variants'][0]['price'],
            'stock_quantity' => $validated['variants'][0]['stock_quantity'],
            'size_id' => $validated['variants'][0]['size_id'] ?? null,
            'color_id' => $validated['variants'][0]['color_id'] ?? null,
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('images', 'public');
                $variant->images()->create([
                    'path' => $path,
                    'is_main' => $index === 0,
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
            'sale' => 'nullable|boolean',
            'hot' => 'nullable|integer|in:0,1,2,3',
            'status' => 'nullable|boolean',
            'variants.0.price' => 'required|numeric|min:0',
            'variants.0.stock_quantity' => 'required|integer|min:0',
            'variants.0.size_id' => 'nullable|exists:sizes,id',
            'variants.0.color_id' => 'nullable|exists:colors,id',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $product->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'category_id' => $validated['category_id'] ?? null,
            'brand_id' => $validated['brand_id'] ?? null,
            'sale' => $validated['sale'] ?? 0,
            'hot' => $validated['hot'] ?? 0,
            'status' => $validated['status'] ?? 1,
            'stock_quantity' => $validated['variants'][0]['stock_quantity'],
        ]);

        $variant = $product->variants()->first();
        if ($variant) {
            $variant->update([
                'price' => $validated['variants'][0]['price'],
                'stock_quantity' => $validated['variants'][0]['stock_quantity'],
                'size_id' => $validated['variants'][0]['size_id'] ?? null,
                'color_id' => $validated['variants'][0]['color_id'] ?? null,
            ]);

            if ($request->hasFile('images')) {
                $variant->images()->delete();
                foreach ($request->file('images') as $index => $image) {
                    $path = $image->store('images', 'public');
                    $variant->images()->create([
                        'path' => $path,
                        'is_main' => $index === 0,
                    ]);
                }
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
