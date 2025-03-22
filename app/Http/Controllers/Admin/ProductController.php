<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::orderBy('name', 'ASC')->get();
        $brands = Brand::orderBy('name', 'ASC')->get();

        $query = Product::with('category', 'brand', 'variants');

        // Tìm kiếm theo tên
        if ($request->has('search_name') && !empty($request->search_name)) {
            $query->where('name', 'like', '%' . $request->search_name . '%');
        }

        // Lọc theo danh mục
        if ($request->has('category_id') && !empty($request->category_id)) {
            $query->where('category_id', $request->category_id);
        }

        $products = $query->get();
        $products = $products->map(function ($product) {
            $product->image_display_url = $product->image_url ? Storage::url($product->image_url) : null;
            return $product;
        });
        return view('admin.products.index', compact('categories', 'brands', 'products'));
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

                if (!$file->isValid()) {
                    throw new \Exception('File ảnh không hợp lệ: ' . $file->getErrorMessage());
                }

                $publicPath = public_path('images/products/new');
                if (!file_exists($publicPath)) {
                    mkdir($publicPath, 0755, true);
                }

                $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move($publicPath, $fileName);
                $imagePath = 'images/products/new/' . $fileName;

                $validatedData['image_url'] = $imagePath;
            }

            $product = Product::create($validatedData);
            $sizeIds = Size::pluck('id')->toArray();
            $colorIds = Color::pluck('id')->toArray();
            $product->variants()->create([
                'size_id' => $sizeIds[0],
                'color_id' => $colorIds[0],
                'stock' => $request->input('stock', 0),
                'sold' => 0,
            ]);
            return redirect()->route('san-pham.index')->with('success', 'Thêm sản phẩm thành công');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Có lỗi xảy ra: ' . $e->getMessage()])->withInput();
        }

    }
    public function show($id)
    {
        $categories = Category::orderBy('name', 'ASC')->get();
        $brands = Brand::orderBy('name', 'ASC')->get();
        $product = Product::findOrFail($id);
        return view('admin.products.show', compact('categories', 'brands', 'product'));
    }

    public function edit($id)
    {
        $categories = Category::orderBy('name', 'ASC')->get();
        $brands = Brand::orderBy('name', 'ASC')->get();
        $product = Product::findOrFail($id);

        return view('admin.products.edit', compact('categories', 'brands', 'product'));
    }

    public function destroy($id)
    {
        try {
            $product = Product::findOrFail($id);

            // Tích hợp logic xóa ảnh
            if (is_string($product->image_url) && !empty($product->image_url)) {
                try {
                    if (Storage::disk('public')->exists($product->image_url)) {
                        Storage::disk('public')->delete($product->image_url);
                    }
                } catch (\Exception $e) {
                    // Bỏ qua lỗi xóa ảnh
                }
            }

            $product->delete();

            return redirect()->route('san-pham.index')->with('success', 'Xóa sản phẩm thành công');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi xóa sản phẩm. Vui lòng thử lại.');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            // Validate dữ liệu từ form
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'price' => 'required|numeric|min:0',
                'category_id' => 'nullable|integer|exists:categories,id',
                'brand_id' => 'nullable|integer|exists:brands,id',
                'img' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'stock' => 'required|integer|min:0',
                'description' => 'nullable|string',
            ]);

            // Tìm sản phẩm
            $product = Product::findOrFail($id);

            // Xử lý ảnh mới nếu có
            if ($request->hasFile('img')) {
                $file = $request->file('img');

                // Xóa ảnh cũ nếu tồn tại
                if (!empty($product->image_url) && file_exists(public_path($product->image_url))) {
                    unlink(public_path($product->image_url));
                }

                // Lưu ảnh mới
                $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
                $publicPath = public_path('images/products/new');

                if (!file_exists($publicPath)) {
                    mkdir($publicPath, 0755, true);
                }

                $file->move($publicPath, $fileName);
                $validatedData['image_url'] = 'images/products/new/' . $fileName;
            } else {
                // Giữ nguyên ảnh cũ
                $validatedData['image_url'] = $product->image_url;
            }

            // Cập nhật thông tin sản phẩm
            $product->update($validatedData);

            // Cập nhật stock trong bảng product_variants
            $variant = $product->variants()->first();
            if ($variant) {
                $variant->update(['stock' => $validatedData['stock']]);
            } else {
                // Tạo mới biến thể nếu chưa có
                $product->variants()->create([
                    'size_id' => 1, // Giá trị mặc định hoặc lấy từ form
                    'color_id' => 1, // Giá trị mặc định hoặc lấy từ form
                    'stock' => $validatedData['stock'],
                    'sold' => 0,
                ]);
            }

            return redirect()->route('san-pham.index')->with('success', 'Cập nhật sản phẩm thành công');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

}