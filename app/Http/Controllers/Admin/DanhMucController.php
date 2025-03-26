<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class DanhMucController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('id', 'asc')->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function show($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        $products = $category->products()->get(); // Ví dụ

        return view('categories.show', [
            'category' => $category,
            'products' => $products
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:categories,slug',
            'img_url' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        try {
            if ($request->hasFile('img_url')) {
                $validatedData['img_url'] = $request->file('img_url')->store('categories', 'public');
            }

            Category::create($validatedData);
            return redirect()->route('danh-muc.index')->with('success', 'Thêm danh mục thành công');
        } catch (\Exception $e) {
            Log::error('Lỗi khi thêm danh mục: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Có lỗi xảy ra khi thêm danh mục!'])->withInput();
        }
    }


    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:categories,slug,' . $id,
            'img_url' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        try {
            $category = Category::findOrFail($id);

            if ($request->hasFile('img_url')) {
                if ($category->img_url) {
                    Storage::disk('public')->delete($category->img_url);
                }
                $validatedData['img_url'] = $request->file('img_url')->store('categories', 'public');
            } else {
                $validatedData['img_url'] = $category->img_url; // Giữ ảnh cũ nếu không upload mới
            }

            $category->update($validatedData);
            return redirect()->route('danh-muc.index')->with('success', 'Cập nhật danh mục thành công');
        } catch (\Exception $e) {
            Log::error('Lỗi khi cập nhật danh mục: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Có lỗi xảy ra khi cập nhật danh mục!']);
        }
    }

    public function destroy($id)
    {
        try {
            $category = Category::findOrFail($id);

            if ($category->img_url) {
                Storage::disk('public')->delete($category->img_url);
            }

            $category->delete();
            return redirect()->route('danh-muc.index')->with('success', 'Xóa danh mục thành công');
        } catch (\Exception $e) {
            Log::error('Lỗi khi xóa danh mục: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Có lỗi xảy ra khi xóa danh mục!']);
        }
    }
}