<?php   
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Color;
use App\Models\Image;
use App\Models\Size;
use Illuminate\Http\Request;

class SanPhamController extends Controller
{
    // Hiển thị danh sách sản phẩm
    public function index()
    {
        $categories = Category::all();
        $brands = Brand::all();
        $sizes = Size::all();
        $colors = Color::all();
        $images = Image::all();

        $products = Product::with([
            'category',
            'brand',
            'variants.size',
            'variants.color',
            'variants.images'
        ])->orderBy('id', 'asc')->get();

        return view('admin.products.index', compact('products', 'categories', 'brands', 'sizes', 'colors', 'images'));
    }


    // Hiển thị form tạo sản phẩm mới
    public function create()
    {
        $categories = Category::all();
        $brands = Brand::all();
        return view('admin.products.create', compact('categories', 'brands'));
    }

    // Lưu sản phẩm mới
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'sale' => 'boolean',
            'hot' => 'integer|min:0|max:3',
            'status' => 'boolean',
            'category_id' => 'nullable|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
        ]);

        $validated['stock_quantity'] = 0; // tổng tồn kho sẽ tự tính từ biến thể
        $validated['sold'] = 0;

        Product::create($validated);

        return redirect()->route('products.index')->with('success', 'Thêm sản phẩm thành công!');
    }

    // Hiển thị form chỉnh sửa sản phẩm
    public function edit(Product $product)
    {
        $categories = Category::all();
        $brands = Brand::all();
        return view('admin.products.edit', compact('product', 'categories', 'brands'));
    }

    // Cập nhật sản phẩm
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'sale' => 'boolean',
            'hot' => 'integer|min:0|max:3',
            'status' => 'boolean',
            'category_id' => 'nullable|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
        ]);

        $product->update($validated);

        return redirect()->route('products.index')->with('success', 'Cập nhật sản phẩm thành công!');
    }

    // Xoá sản phẩm
    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Xoá sản phẩm thành công!');
    }

    // Xem chi tiết sản phẩm
    public function show(Product $product)
    {
        $product->load(['category', 'brand', 'variants.size', 'variants.color', 'variants.images']);
        return view('admin.products.show', compact('product'));
    }
}
