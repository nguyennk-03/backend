<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Size;
use App\Models\Color;
use App\Models\ProductVariant as Variant;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SanPhamController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'brand', 'variants.images']);

        // Lọc theo category_id
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Lọc theo brand_id
        if ($request->filled('brand_id')) {
            $query->where('brand_id', $request->brand_id);
        }

        // Lọc theo khoảng giá (dựa trên discounted_price của variants)
        if ($request->filled('min_price')) {
            $query->whereHas('variants', function ($q) use ($request) {
                $q->where('discounted_price', '>=', $request->min_price);
            });
        }
        if ($request->filled('max_price')) {
            $query->whereHas('variants', function ($q) use ($request) {
                $q->where('discounted_price', '<=', $request->max_price);
            });
        }

        // Sắp xếp
        if ($request->filled('sort_by')) {
            switch ($request->sort_by) {
                case 'price_asc':
                    $query->join('variants', 'products.id', '=', 'variants.product_id')
                        ->orderBy('variants.discounted_price', 'asc');
                    break;
                case 'price_desc':
                    $query->join('variants', 'products.id', '=', 'variants.product_id')
                        ->orderBy('variants.discounted_price', 'desc');
                    break;
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'oldest':
                    $query->orderBy('created_at', 'asc');
                    break;
                default:
                    $query->orderBy('created_at', 'desc');
                    break;
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        // Lấy dữ liệu
        $products = $query->distinct()->get();

        // Dữ liệu cho form và modal
        $categories = Category::all();
        $brands = Brand::all();
        $sizes = Size::all();
        $colors = Color::all();

        return view('admin.products.index', compact('products', 'categories', 'brands', 'sizes', 'colors'));
    }
    public function create()
    {
        $sizes = Size::all();
        $colors = Color::all();
        return view('admin.products.create', compact('sizes', 'colors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'variants' => 'required|array',
            'variants.*.size_id-dates' => 'required|exists:sizes,id',
            'variants.*.color_id' => 'required|exists:colors,id',
            'variants.*.price' => 'required|numeric|min:0',
            'variants.*.discount_percent' => 'nullable|integer|min:0|max:100',
            'variants.*.stock_quantity' => 'required|integer|min:0',
            'variants.*.images.*' => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $product = Product::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        foreach ($request->variants as $variantData) {
            $discountedPrice = $variantData['price'] * (1 - ($variantData['discount_percent'] ?? 0) / 100);
            $variant = Variant::create([
                'product_id' => $product->id,
                'size_id' => $variantData['size_id'],
                'color_id' => $variantData['color_id'],
                'price' => $variantData['price'],
                'discount_percent' => $variantData['discount_percent'] ?? 0,
                'discounted_price' => $discountedPrice,
                'stock_quantity' => $variantData['stock_quantity'],
                'sold' => 0,
            ]);

            if (isset($variantData['images'])) {
                foreach ($variantData['images'] as $index => $image) {
                    $path = $image->store('images/products', 'public');
                    Image::create([
                        'variant_id' => $variant->id,
                        'path' => $path,
                        'is_main' => $index === 0,
                    ]);
                }
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        $sizes = Size::all();
        $colors = Color::all();
        $product->load('variants.size', 'variants.color', 'variants.images');
        return view('admin.products.edit', compact('product', 'sizes', 'colors'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'variants' => 'required|array',
            'variants.*.size_id' => 'required|exists:sizes,id',
            'variants.*.color_id' => 'required|exists:colors,id',
            'variants.*.price' => 'required|numeric|min:0',
            'variants.*.discount_percent' => 'nullable|integer|min:0|max:100',
            'variants.*.stock_quantity' => 'required|integer|min:0',
            'variants.*.images.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $product->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        // Update or create variants
        $existingVariantIds = $product->variants->pluck('id')->toArray();
        $submittedVariantIds = array_filter(array_column($request->variants, 'id'));

        // Delete removed variants
        foreach ($existingVariantIds as $variantId) {
            if (!in_array($variantId, $submittedVariantIds)) {
                $variant = Variant::find($variantId);
                foreach ($variant->images as $image) {
                    Storage::disk('public')->delete($image->path);
                    $image->delete();
                }
                $variant->delete();
            }
        }

        // Update or create variants
        foreach ($request->variants as $variantData) {
            $discountedPrice = $variantData['price'] * (1 - ($variantData['discount_percent'] ?? 0) / 100);
            $variant = Variant::updateOrCreate(
                ['id' => $variantData['id'] ?? null],
                [
                    'product_id' => $product->id,
                    'size_id' => $variantData['size_id'],
                    'color_id' => $variantData['color_id'],
                    'price' => $variantData['price'],
                    'discount_percent' => $variantData['discount_percent'] ?? 0,
                    'discounted_price' => $discountedPrice,
                    'stock_quantity' => $variantData['stock_quantity'],
                ]
            );

            if (isset($variantData['images'])) {
                foreach ($variantData['images'] as $index => $image) {
                    $path = $image->store('images/products', 'public');
                    Image::create([
                        'variant_id' => $variant->id,
                        'path' => $path,
                        'is_main' => $index === 0,
                    ]);
                }
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        foreach ($product->variants as $variant) {
            foreach ($variant->images as $image) {
                Storage::disk('public')->delete($image->path);
                $image->delete();
            }
            $variant->delete();
        }
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
    }
}
