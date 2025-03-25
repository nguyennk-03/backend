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
        $categories = Category::orderBy('name')->get();
        $brands = Brand::orderBy('name')->get();

        $products = Product::with('category', 'brand', 'variants')
            ->when($request->search_name, fn($q) => $q->where('name', 'like', "%{$request->search_name}%"))
            ->when($request->category_id, fn($q) => $q->where('category_id', $request->category_id))
            ->get()
            ->map(fn($product) => $product->setAttribute('image_display_url', $product->image_url ? Storage::url($product->image_url) : null));

        return view('admin.products.index', compact('categories', 'brands', 'products'));
    }
    public function show(Request $request, $id)
    {
        try {
            $product = Product::with([
                'category:id,name',
                'brand:id,name',
                'variants:size_id,color_id,stock,sold'
            ])->findOrFail($id);

            // Chỉ lấy categories và brands nếu cần cho chỉnh sửa
            $categories = $request->has('edit') ? Category::orderBy('name', 'ASC')->get() : null;
            $brands = $request->has('edit') ? Brand::orderBy('name', 'ASC')->get() : null;

            return view('admin.products.show', compact('product', 'categories', 'brands'));
        } catch (\Exception $e) {
            return redirect()->route('san-pham.index')->with('error', 'Không tìm thấy sản phẩm.');
        }
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
                $validatedData['image_url'] = 'images/products/new/' . $fileName;
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
            $product = Product::findOrFail($id);

            if ($request->hasFile('img')) {
                if ($product->image_url && file_exists(public_path($product->image_url))) {
                    unlink(public_path($product->image_url));
                }
                $file = $request->file('img');
                $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('images/products/new'), $fileName);
                $validatedData['image_url'] = 'images/products/new/' . $fileName;
            }

            $product->update($validatedData);
            $product->variants()->updateOrCreate(
                ['product_id' => $product->id],
                ['stock' => $validatedData['stock'], 'size_id' => 1, 'color_id' => 1, 'sold' => 0]
            );

            return redirect()->route('san-pham.index')->with('success', 'Cập nhật sản phẩm thành công');
        } catch (\Exception $e) {
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $product = Product::findOrFail($id);
            if ($product->image_url && file_exists(public_path($product->image_url))) {
                unlink(public_path($product->image_url));
            }
            $product->delete();

            return redirect()->route('san-pham.index')->with('success', 'Xóa sản phẩm thành công');
        } catch (\Exception $e) {
            return back()->with('error', 'Có lỗi xảy ra khi xóa sản phẩm: ' . $e->getMessage());
        }
    }
}
