<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Str; // Added this import

class BaiVietController extends Controller
{
    public function index(Request $request)
    {
        $query = News::query();

        // Filter by category
        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        $news = $query->paginate(10);
        $categories = Category::all(); // For filter dropdown
        return view('admin.news.index', compact('news', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'author' => 'required|string|max:255',
        ]);

        $data = $request->only('title', 'content', 'category_id', 'brand_id', 'author');
        $data['slug'] = Str::slug($request->title);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('news', 'public');
        }

        News::create($data);

        return redirect()->route('bai-viet.index')->with('success', 'Bài viết đã được thêm!');
    }

    public function update(Request $request, News $news)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'author' => 'required|string|max:255',
        ]);

        $data = $request->only('title', 'content', 'category_id', 'brand_id', 'author');
        $data['slug'] = Str::slug($request->title);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('news', 'public');
        }

        $news->update($data);

        return redirect()->route('bai-viet.index')->with('success', 'Bài viết đã được cập nhật!');
    }

    public function destroy(News $news)
    {
        $news->delete();
        return redirect()->route('bai-viet.index')->with('success', 'Bài viết đã được xóa!');
    }
}
