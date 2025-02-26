<?php

namespace App\Http\Controllers\Admin;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        $categories = Category::all();
        $brands = Brand::all();
        return view('admin.products.index', compact('products', 'categories', 'brands'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'category_id' => 'required|integer',
            'brand_id' => 'required|integer',
            'img' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $product = new Product($request->all());
        if ($request->hasFile('img')) {
            $product->variant_image = $request->file('img')->store('products', 'public');
        }
        $product->save();

        return redirect()->route('products.index')->with('success', 'Thêm sản phẩm thành công');
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();
        $brands = Brand::all();
        return view('admin.products.edit', compact('product', 'categories', 'brands'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $product->update($request->all());

        if ($request->hasFile('img')) {
            $product->variant_image = $request->file('img')->store('products', 'public');
            $product->save();
        }

        return redirect()->route('products')->with('success', 'Cập nhật sản phẩm thành công');
    }

    public function destroy($id)
    {
        Product::destroy($id);
        return redirect()->route('products')->with('success', 'Xóa sản phẩm thành công');
    }
}
