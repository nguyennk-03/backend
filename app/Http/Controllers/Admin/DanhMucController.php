<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;

class DanhMucController extends Controller
{
    /**
     * Display a listing of categories.
     */
    public function index(Request $request)
    {
        $query = Category::query();

        // Apply sorting based on request
        if ($request->sort_by) {
            switch ($request->sort_by) {
                case 'name_asc':
                    $query->orderBy('name', 'asc');
                    break;
                case 'name_desc':
                    $query->orderBy('name', 'desc');
                    break;
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'oldest':
                    $query->orderBy('created_at', 'asc');
                    break;
            }
        } else {
            // Default sort by name ascending
            $query->orderBy('name', 'asc');
        }

        $categories = $query->get();
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Store a newly created category.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'status' => 'required|in:0,1',
        ]);

        try {
            $validatedData['name'] = trim($validatedData['name']);

            // Handle name uniqueness
            $originalName = $validatedData['name'];
            $count = 1;
            while (Category::where('name', $validatedData['name'])->exists()) {
                $validatedData['name'] = $originalName . '-' . $count++;
            }

            // Handle image upload using Storage
            if ($request->hasFile('image')) {
                $validatedData['image'] = $request->file('image')->store('categories', 'public');
            }

            Category::create($validatedData);
            return redirect()->route('danh-muc.index')->with('success', 'Thêm danh mục thành công');
        } catch (QueryException $e) {
            Log::error('Lỗi khi thêm danh mục: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Có lỗi xảy ra khi thêm danh mục!'])->withInput();
        }
    }

    /**
     * Update the specified category.
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'status' => 'required|in:0,1',
        ]);

        try {
            $category = Category::findOrFail($id);
            $validatedData['name'] = trim($validatedData['name']);

            // Handle name uniqueness
            $originalName = $validatedData['name'];
            $count = 1;
            while (Category::where('name', $validatedData['name'])->where('id', '!=', $id)->exists()) {
                $validatedData['name'] = $originalName . '-' . $count++;
            }

            // Handle image upload and delete old image if exists
            if ($request->hasFile('image')) {
                if ($category->image) {
                    Storage::disk('public')->delete($category->image);
                }
                $validatedData['image'] = $request->file('image')->store('categories', 'public');
            }

            $category->update($validatedData);
            return redirect()->route('danh-muc.index')->with('success', 'Cập nhật danh mục thành công');
        } catch (QueryException $e) {
            Log::error('Lỗi khi cập nhật danh mục: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Có lỗi xảy ra khi cập nhật danh mục!'])->withInput();
        }
    }

    /**
     * Remove the specified category.
     */
    public function destroy($id)
    {
        try {
            $category = Category::findOrFail($id);

            // Delete associated image if exists
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }

            $category->delete();
            return redirect()->route('danh-muc.index')->with('success', 'Xóa danh mục thành công');
        } catch (QueryException $e) {
            Log::error('Lỗi khi xóa danh mục: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Có lỗi xảy ra khi xóa danh mục!']);
        }
    }
}
