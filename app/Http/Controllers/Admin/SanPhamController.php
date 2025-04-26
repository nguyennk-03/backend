<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SanPhamController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category', 'brand'); // Chỉ lấy sản phẩm chưa bị xóa mềm

        // Áp dụng bộ lọc
        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->brand_id) {
            $query->where('brand_id', $request->brand_id);
        }
        if ($request->has('sale')) {
            $query->where('sale', $request->sale);
        }
        if ($request->has('hot')) {
            $query->where('hot', $request->hot);
        }
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        if ($request->min_price) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->max_price) {
            $query->where('price', '<=', $request->max_price);
        }

        // Áp dụng sắp xếp
        switch ($request->sort_by) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            default:
                $query->orderBy('id', 'desc');
                break;
        }

        $sizes = Product::select('size')->distinct()->pluck('size');
        $colors = Product::select('color')->distinct()->pluck('color');
        $products = $query->get();
        $categories = Category::all();
        $brands = Brand::all();

        return view('admin.products.index', compact('products', 'categories', 'brands', 'sizes', 'colors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'category_id' => 'nullable|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'status' => 'required|boolean',
            'sale' => 'required|boolean',
            'hot' => 'required|in:0,1,2,3',
        ]);

        $data = $validated;

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('images/products', 'public');
        }

        Product::create($data);

        return redirect()->route('san-pham.index')->with('success', 'Sản phẩm đã được thêm thành công!');
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'category_id' => 'nullable|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'status' => 'required|boolean',
            'sale' => 'required|boolean',
            'hot' => 'required|in:0,1,2,3',
        ]);

        $data = $validated;

        if ($request->hasFile('image')) {
            // Xóa hình ảnh cũ nếu tồn tại
            if (!empty($product->image) && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            // Lưu hình ảnh mới
            $data['image'] = $request->file('image')->store('images/products', 'public');
        }

        $product->update($data);

        return redirect()->route('san-pham.index')->with('success', 'Sản phẩm đã được cập nhật thành công!');
    }

    public function destroy(Product $product)
    {
        try {
            // Xóa hình ảnh nếu tồn tại
            if (!empty($product->image) && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }

            // Xóa sản phẩm
            $deleted = $product->delete();

            if (!$deleted) {
                throw new \Exception('Không thể xóa sản phẩm. Có thể do ràng buộc khóa ngoại hoặc lỗi cơ sở dữ liệu.');
            }

            return redirect()->route('san-pham.index')->with('success', 'Sản phẩm đã được xóa thành công!');
        } catch (\Exception $e) {
            return redirect()->route('san-pham.index')->with('error', 'Có lỗi xảy ra khi xóa sản phẩm: ' . $e->getMessage());
        }
    }
}
