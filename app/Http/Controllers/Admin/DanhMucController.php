<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class DanhMucController extends Controller
{
    public function index()
    {
        $categories = Category::all();

        return view('admin.categories.index', compact('categories'));
    }


    public function show($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        $products = $category->products()->get();
        return view('categories.show', compact('category', 'products'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'parent_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
        ]);

        try {
            $validatedData['name'] = trim($validatedData['name']);
            $validatedData['slug'] = Str::slug($validatedData['name']);

            $originalSlug = $validatedData['slug'];
            $count = 1;
            while (Category::where('slug', $validatedData['slug'])->exists()) {
                $validatedData['slug'] = $originalSlug . '-' . $count++;
            }

            if ($request->hasFile('image')) {
                $file = $request->file('image');

                if (!$file->isValid()) {
                    throw new \Exception('File ảnh không hợp lệ: ' . $file->getErrorMessage());
                }

                $publicPath = public_path('images/categories/store');
                if (!file_exists($publicPath)) {
                    mkdir($publicPath, 0755, true);
                }

                $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move($publicPath, $fileName);
                $validatedData['image'] = 'images/categories/store/' . $fileName;
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
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'parent_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
        ]);

        try {
            $category = Category::findOrFail($id);
            $validatedData['name'] = trim($validatedData['name']);
            $validatedData['slug'] = Str::slug($validatedData['name']);

            $originalSlug = $validatedData['slug'];
            $count = 1;
            while (Category::where('slug', $validatedData['slug'])->where('id', '!=', $id)->exists()) {
                $validatedData['slug'] = $originalSlug . '-' . $count++;
            }

            if ($request->hasFile('image')) {
                $file = $request->file('image');

                if (!$file->isValid()) {
                    throw new \Exception('File ảnh không hợp lệ: ' . $file->getErrorMessage());
                }

                $publicPath = public_path('images/categories/update');
                if (!file_exists($publicPath)) {
                    mkdir($publicPath, 0755, true);
                }

                $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move($publicPath, $fileName);
                $validatedData['image'] = 'images/categories/update/' . $fileName;
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

            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }

            $category->delete();
            return redirect()->route('danh-muc.index')->with('success', 'Xóa danh mục thành công');
        } catch (\Exception $e) {
            Log::error('Lỗi khi xóa danh mục: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Có lỗi xảy ra khi xóa danh mục!']);
        }
    }
}