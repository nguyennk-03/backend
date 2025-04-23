<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant as Variant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SanPhamController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'brand', 'variants']);

        // Lọc theo category_id
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Lọc theo brand_id
        if ($request->filled('brand_id')) {
            $query->where('brand_id', $request->brand_id);
        }

        // Lọc theo khoảng giá (dựa trên price của product hoặc discounted_price của variants)
        if ($request->filled('min_price')) {
            $query->where(function ($q) use ($request) {
                $q->where('price', '>=', $request->min_price)
                    ->orWhereHas('variants', function ($qv) use ($request) {
                        $qv->where('discounted_price', '>=', $request->min_price);
                    });
            });
        }
        if ($request->filled('max_price')) {
            $query->where(function ($q) use ($request) {
                $q->where('price', '<=', $request->max_price)
                    ->orWhereHas('variants', function ($qv) use ($request) {
                        $qv->where('discounted_price', '<=', $request->max_price);
                    });
            });
        }

        // Lọc theo trạng thái
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Lọc theo sale, hot
        if ($request->filled('sale')) {
            $query->where('sale', $request->sale);
        }
        if ($request->filled('hot')) {
            $query->where('hot', $request->hot);
        }

        // Sắp xếp
        if ($request->filled('sort_by')) {
            switch ($request->sort_by) {
                case 'price_asc':
                    $query->leftJoin('product_variants', 'products.id', '=', 'product_variants.product_id')
                        ->select('products.*')
                        ->groupBy('products.id')
                        ->orderByRaw('COALESCE(MIN(product_variants.discounted_price), products.price) ASC');
                    break;
                case 'price_desc':
                    $query->leftJoin('product_variants', 'products.id', '=', 'product_variants.product_id')
                        ->select('products.*')
                        ->groupBy('products.id')
                        ->orderByRaw('COALESCE(MAX(product_variants.discounted_price), products.price) DESC');
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

        // Phân trang
        $products = $query->get();

        $categories = Category::all();
        $brands = Brand::all();

        return view('admin.products.index', compact('products', 'categories', 'brands'));
    }

    public function create()
    {
        $categories = Category::all();
        $brands = Brand::all();
        return view('admin.products.create', compact('categories', 'brands'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'description' => 'nullable|string',
            'sale' => 'required|in:0,1',
            'hot' => 'required|in:0,1,2,3',
            'status' => 'required|in:0,1',
            'category_id' => 'nullable|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'variants' => 'nullable|array',
            'variants.*.size' => 'nullable|string|max:50',
            'variants.*.color' => 'nullable|string|max:50',
            'variants.*.image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'variants.*.discount_percent' => 'nullable|integer|min:0|max:100',
            'variants.*.stock_quantity' => 'required|integer|min:0',
        ]);

        try {
            DB::beginTransaction();

            // Xử lý hình ảnh sản phẩm
            $imagePath = $request->file('image') ? $request->file('image')->store('images/products', 'public') : null;

            // Tính tổng stock_quantity từ variants (nếu có)
            $stockQuantity = $request->variants ? array_sum(array_column($request->variants, 'stock_quantity')) : 0;

            $product = Product::create([
                'name' => $request->name,
                'price' => $request->price,
                'image' => $imagePath,
                'description' => $request->description ? Str::sanitize($request->description) : null,
                'sale' => $request->sale,
                'hot' => $request->hot,
                'status' => $request->status,
                'stock_quantity' => $stockQuantity,
                'sold' => 0,
                'category_id' => $request->category_id,
                'brand_id' => $request->brand_id,
            ]);

            if ($request->variants) {
                foreach ($request->variants as $variantData) {
                    $discountedPrice = $variantData['discount_percent']
                        ? $product->price * (1 - $variantData['discount_percent'] / 100)
                        : null;
                    $variantImagePath = isset($variantData['image'])
                        ? $variantData['image']->store('images/products', 'public')
                        : null;

                    Variant::create([
                        'product_id' => $product->id,
                        'size' => $variantData['size'] ?? null,
                        'color' => $variantData['color'] ?? null,
                        'image' => $variantImagePath,
                        'discount_percent' => $variantData['discount_percent'] ?? 0,
                        'discounted_price' => $discountedPrice,
                        'stock_quantity' => $variantData['stock_quantity'],
                        'sold' => 0,
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }
            return back()->withErrors(['error' => 'Failed to create product: ' . $e->getMessage()]);
        }
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        $brands = Brand::all();
        $product->load('variants');
        return view('admin.products.edit', compact('product', 'categories', 'brands'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'description' => 'nullable|string',
            'sale' => 'required|in:0,1',
            'hot' => 'required|in:0,1,2,3',
            'status' => 'required|in:0,1',
            'category_id' => 'nullable|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'variants' => 'nullable|array',
            'variants.*.size' => 'nullable|string|max:50',
            'variants.*.color' => 'nullable|string|max:50',
            'variants.*.image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'variants.*.discount_percent' => 'nullable|integer|min:0|max:100',
            'variants.*.stock_quantity' => 'required|integer|min:0',
        ]);

        try {
            DB::beginTransaction();

            // Xử lý hình ảnh sản phẩm
            $imagePath = $product->image;
            if ($request->file('image')) {
                if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                    Storage::disk('public')->delete($imagePath);
                }
                $imagePath = $request->file('image')->store('images/products', 'public');
            }

            // Tính tổng stock_quantity từ variants
            $stockQuantity = $request->variants ? array_sum(array_column($request->variants, 'stock_quantity')) : 0;

            $product->update([
                'name' => $request->name,
                'price' => $request->price,
                'image' => $imagePath,
                'description' => $request->description ? Str::sanitize($request->description) : null,
                'sale' => $request->sale,
                'hot' => $request->hot,
                'status' => $request->status,
                'stock_quantity' => $stockQuantity,
                'category_id' => $request->category_id,
                'brand_id' => $request->brand_id,
            ]);

            // Xử lý variants
            $existingVariantIds = $product->variants->pluck('id')->toArray();
            $submittedVariantIds = array_filter(array_column($request->variants ?? [], 'id'));

            // Xóa variants đã bị loại bỏ
            foreach ($existingVariantIds as $variantId) {
                if (!in_array($variantId, $submittedVariantIds)) {
                    $variant = Variant::find($variantId);
                    if ($variant->image && Storage::disk('public')->exists($variant->image)) {
                        Storage::disk('public')->delete($variant->image);
                    }
                    $variant->delete();
                }
            }

            // Cập nhật hoặc tạo variants
            if ($request->variants) {
                foreach ($request->variants as $variantData) {
                    $discountedPrice = $variantData['discount_percent']
                        ? $product->price * (1 - $variantData['discount_percent'] / 100)
                        : null;
                    $variantImagePath = $variantData['id'] ? Variant::find($variantData['id'])->image : null;

                    if (isset($variantData['image'])) {
                        if ($variantImagePath && Storage::disk('public')->exists($variantImagePath)) {
                            Storage::disk('public')->delete($variantImagePath);
                        }
                        $variantImagePath = $variantData['image']->store('images/products', 'public');
                    }

                    Variant::updateOrCreate(
                        ['id' => $variantData['id'] ?? null],
                        [
                            'product_id' => $product->id,
                            'size' => $variantData['size'] ?? null,
                            'color' => $variantData['color'] ?? null,
                            'image' => $variantImagePath,
                            'discount_percent' => $variantData['discount_percent'] ?? 0,
                            'discounted_price' => $discountedPrice,
                            'stock_quantity' => $variantData['stock_quantity'],
                            'sold' => $variantData['id'] ? Variant::find($variantData['id'])->sold : 0,
                        ]
                    );
                }
            }

            DB::commit();
            return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to update product: ' . $e->getMessage()]);
        }
    }

    public function destroy(Product $product)
    {
        try {
            DB::beginTransaction();

            foreach ($product->variants as $variant) {
                if ($variant->image && Storage::disk('public')->exists($variant->image)) {
                    Storage::disk('public')->delete($variant->image);
                }
                $variant->delete();
            }

            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $product->delete();

            DB::commit();
            return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to delete product: ' . $e->getMessage()]);
        }
    }
}
