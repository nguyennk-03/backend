<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Brand, Category, Color, Product, ProductVariant, Size};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class SanPhamController extends Controller
{
    public function index(Request $request)
    {
        $categories = category::orderBy('name', 'ASC')->get();
        $brands = Brand::orderBy('name', 'ASC')->get();
        $products = Product::with(['category:id,name', 'brand:id,name'])->orderBy('id', 'DESC')->get();

        return view('admin.products.index', compact('categories', 'brands', 'products'));
    }

    public function show($slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();

        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->limit(4)
            ->get(); 

        return view('products.show', [
            'product' => $product,
            'relatedProducts' => $relatedProducts
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'brand_id' => 'nullable|integer|exists:brands,id',
            'category_id' => 'nullable|integer|exists:categories,id',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'variant.*.stock' => 'required|integer|min:0',
            'img' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        try {
            if ($request->hasFile('img')) {
                $file = $request->file('img');
                $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('images/products/new'), $fileName);
                $validatedData['image'] = 'images/products/new/' . $fileName;
            }

            $product = Product::create($validatedData);
            $product->variants()->create([
                'size_id' => Size::value('id') ?? 1,
                'color_id' => Color::value('id') ?? 1,
                'stock' => $request->input('stock', 0),
                'sold' => 0,
            ]);

            return redirect()->route('san-pham.index')->with('success', 'Thêm sản phẩm thành công');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Có lỗi xảy ra: ' . $e->getMessage()])->withInput();
        }
    }

    public function update(Request $request, $id)
    {
        // Validate dữ liệu
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'category_id' => 'nullable|integer|exists:categories,id',
            'brand_id' => 'nullable|integer|exists:brands,id',
            'img' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
        ]);

        try {
            // Tìm sản phẩm
            $product = Product::findOrFail($id);

            // Xử lý upload ảnh
            if ($request->hasFile('img')) {
                // Xóa ảnh cũ nếu tồn tại
                if ($product->image && file_exists(public_path($product->image))) {
                    unlink(public_path($product->image));
                }
                $file = $request->file('img');
                $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('images/products/new'), $fileName);
                $validatedData['image'] = 'images/products/new/' . $fileName;
            }

            // Cập nhật sản phẩm
            $product->update($validatedData);

            // Cập nhật hoặc tạo variant (giả sử có bảng variants)
            $product->variants()->updateOrCreate(
                ['product_id' => $product->id],
                [
                    'stock' => $validatedData['stock'],
                    'size_id' => 1,  
                    'color_id' => 1, // Thay bằng logic thực tế nếu cần
                    'sold' => 0
                ]
            );

            return redirect()->route('san-pham.index')->with('success', 'Cập nhật sản phẩm thành công');
        } catch (\Exception $e) {
            Log::error('Lỗi khi cập nhật sản phẩm: ' . $e->getMessage());
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $product = Product::findOrFail($id);
            if ($product->image && file_exists(public_path($product->image))) {
                unlink(public_path($product->image));
            }
            $product->delete();

            return redirect()->route('san-pham.index')->with('success', 'Xóa sản phẩm thành công');
        } catch (\Exception $e) {
            return back()->with('error', 'Có lỗi xảy ra khi xóa sản phẩm: ' . $e->getMessage());
        }
    }
}
