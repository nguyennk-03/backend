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
    public function show(Request $request, $id)
    {
        try {
            $product = Product::with([
                'category:id,name',
                'brand:id,name',
                'variants:size_id,color_id,stock,sold'
            ])->findOrFail($id);

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
            'stock' => 'required|integer|min:0', // Added consistency with update()
            'img' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        try {
            // Handle image upload consistently with update()
            if ($request->hasFile('img')) {
                $imagePath = $request->file('img')->store('images/products/new', 'public');
                $validatedData['image'] = $imagePath;
            }

            $product = Product::create($validatedData);

            $product->variants()->create([
                'size_id' => $request->input('size_id', 1),  // Make configurable
                'color_id' => $request->input('color_id', 1), // Make configurable
                'stock' => $validatedData['stock'],
                'sold' => 0,
            ]);

            return redirect()->route('san-pham.index')->with('success', 'Thêm sản phẩm thành công');
        } catch (\Exception $e) {
            Log::error('Product creation failed: ' . $e->getMessage());
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())->withInput();
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

            // Handle image upload
            if ($request->hasFile('img')) {
                // Delete old image if exists
                if ($product->image && Storage::disk('public')->exists($product->image)) {
                    Storage::disk('public')->delete($product->image);
                }

                $imagePath = $request->file('img')->store('images/products/new', 'public');
                $validatedData['image'] = $imagePath;
            } else {
                unset($validatedData['img']);
            }

            // Update product
            $product->update($validatedData);

            // Update or create variant with more flexible parameters
            $product->variants()->updateOrCreate(
                ['product_id' => $product->id],
                [
                    'stock' => $validatedData['stock'],
                    'size_id' => $request->input('size_id', 1),  // Make configurable
                    'color_id' => $request->input('color_id', 1), // Make configurable
                    'sold' => $product->variants()->first()->sold ?? 0 // Preserve existing sold value
                ]
            );

            return redirect()->route('san-pham.index')->with('success', 'Cập nhật sản phẩm thành công');
        } catch (\Exception $e) {
            Log::error('Product update failed: ' . $e->getMessage());
            return back()->with('error', 'Có lỗi xảy ra khi cập nhật sản phẩm')->withInput();
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
