<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class ProductController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('name', 'ASC')->get();
        $brands = Brand::orderBy('name', 'ASC')->get();
        $products = Product::with('category', 'brand')->get();
        return view('admin.products.index', compact('categories', 'brands', 'products'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'category_id' => 'required|integer|exists:categories,id',
            'brand_id' => 'nullable|integer|exists:brands,id',
            'quantity' => 'required|integer',
            'images.*' => 'file|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $validatedData['slug'] = Str::slug($validatedData['name']);
        if (Product::where('slug', $validatedData['slug'])->exists()) {
            $validatedData['slug'] .= '-' . time();
        }

        // Tạo sản phẩm
        $product = Product::create($validatedData);

        // Xử lý nhiều ảnh
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                Image::create([
                    'product_id' => $product->id,
                    'image_path' => $path
                ]);
            }
        }

        return redirect()->route('products')->with('success', 'Thêm sản phẩm thành công');
    }

    public function edit($id)
    {
        $categories = Category::orderBy('name', 'ASC')->get();
        $brands = Brand::orderBy('name', 'ASC')->get();
        $product = Product::findOrFail($id);

        return view('admin.products.index', compact('categories', 'brands', 'product'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'category_id' => 'required|integer|exists:categories,id',
            'brand_id' => 'nullable|integer|exists:brands,id',
            'img' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'quantity' => 'required|integer',
            'description' => 'nullable|string',
        ]);

        $product = Product::findOrFail($id);

        if ($request->hasFile('img')) {
            $this->deleteImage($product->img); // Xóa ảnh cũ
            $validatedData['img'] = $this->uploadImage($request->file('img'));
        }

        $product->update($validatedData);

        return redirect()->route('products')->with('success', 'Cập nhật sản phẩm thành công');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $this->deleteImage($product->img);
        $product->delete();

        return redirect()->route('products')->with('success', 'Xóa sản phẩm thành công');
    }

    private function uploadImage($file)
    {
        $imgName = time() . '.' . $file->extension();
        $file->move(public_path('uploads'), $imgName);
        return $imgName;
    }

    private function deleteImage($imgPath)
    {
        if ($imgPath) {
            $fullPath = public_path("uploads/{$imgPath}");
            if (File::exists($fullPath)) {
                File::delete($fullPath);
            }
        }
    }
}
