<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Brand, Category, Color, Product, ProductVariant, Size};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SanPhamController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::orderBy('id', 'asc')->get();
        $brands = Brand::orderBy('id', 'asc')->get();
        $query = Product::with('variants');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('brand_id')) {
            $query->where('brand_id', $request->brand_id);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Xử lý khoảng giá
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        if ($request->filled('sort_by')) {
            switch ($request->sort_by) {
                case 'created_at_desc':
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'created_at_asc':
                case 'oldest':
                    $query->orderBy('created_at', 'asc');
                    break;
                case 'name_asc':
                    $query->orderBy('name', 'asc');
                    break;
                case 'name_desc':
                    $query->orderBy('name', 'desc');
                    break;
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
            }
        }

        $products = $query->get()->map(function ($product) {
            $product->img_display_url = $product->image ? asset($product->image) : null;
            $product->total_stock = $product->variants->sum('stock'); // Tính tổng stock từ variants
            return $product;
        });

        return view('admin.products.index', compact('categories', 'brands', 'products'));
    }

    public function show($id)
    {
        try {
            $product = Product::with('category', 'brand', 'variants')->findOrFail($id);
            return response()->json(['success' => true, 'product' => $product]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy sản phẩm']);
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
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',    
        ]);

        try {
            if ($request->hasFile('image')) {
                $file = $request->file('image');

                if (!$file->isValid()) {
                    throw new \Exception('File ảnh không hợp lệ: ' . $file->getErrorMessage());
                }

                $publicPath = public_path('images/products/store');
                if (!file_exists($publicPath)) {
                    mkdir($publicPath, 0755, true);
                }

                $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move($publicPath, $fileName);
                $validatedData['image'] = 'images/products/store/' . $fileName;
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
            'brand_id' => 'nullable|integer|exists:brands,id',
            'category_id' => 'nullable|integer|exists:categories,id',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'variant.*.stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        try {
            $product = Product::findOrFail($id);

            if ($request->hasFile('image')) {
                $file = $request->file('image');

                if (!$file->isValid()) {
                    throw new \Exception('File ảnh không hợp lệ: ' . $file->getErrorMessage());
                }

                $publicPath = public_path('images/products/update');
                if (!file_exists($publicPath)) {
                    mkdir($publicPath, 0755, true);
                }

                $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move($publicPath, $fileName);
                $validatedData['image'] = 'images/products/update/' . $fileName;
            }

            $product->update($validatedData);

            $product->variants()->updateOrCreate(
                ['product_id' => $product->id],
                [
                    'size_id' => Size::first()->id ?? 1,
                    'color_id' => Color::first()->id ?? 1,
                    'stock' => $request->input('stock', 0),
                    'sold' => $product->variants()->value('sold') ?? 0,
                ]
            );

            return redirect()->route('san-pham.index')->with('success', 'Cập nhật sản phẩm thành công');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Có lỗi xảy ra: ' . $e->getMessage()])->withInput();
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