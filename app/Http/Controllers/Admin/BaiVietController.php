<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class BaiVietController extends Controller
{
    /**
     * Display a listing of the news articles.
     */
    public function index(Request $request)
    {
        $query = News::with(['category', 'brand']);

        // Filter by category
        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by brand
        if ($request->brand_id) {
            $query->where('brand_id', $request->brand_id);
        }

        // Filter by status
        if ($request->status !== null) {
            $query->where('status', $request->status);
        }

        // Add search functionality
        if ($request->search) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $news = $query->orderBy('created_at', 'desc')->get();
        $categories = Category::all();
        $brands = Brand::all();

        return view('admin.news.index', compact('news', 'categories', 'brands'));
    }

    /**
     * Show the form for creating a new news article.
     */
    public function create()
    {
        $categories = Category::all();
        $brands = Brand::all();
        return view('admin.news.create', compact('categories', 'brands'));
    }

    /**
     * Store a newly created news article.
     */
    public function store(Request $request)
    {
        // Nếu không nhập slug, tạo slug từ title
        if (!$request->filled('slug') && $request->filled('title')) {
            $request->merge([
                'slug' => Str::slug($request->input('title'))
            ]);
        }

        // Validate dữ liệu
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:news',
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'author' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $data = $validated;

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('images/news', 'public');
        }

        $data['views'] = 0;

        News::create($data);

        return redirect()->route('bai-viet.index')->with('success', 'Bài viết đã được tạo thành công!');
    }

    /**
     * Display the specified news article.
     */
    public function show(News $news)
    {
        $news->increment('views');
        return view('admin.news.show', compact('news'));
    }

    /**
     * Show the form for editing a news article.
     */
    public function edit(News $news)
    {
        $categories = Category::all();
        $brands = Brand::all();
        return view('admin.news.edit', compact('news', 'categories', 'brands'));
    }

    /**
     * Update the specified news article.
     */
    public function update(Request $request, $id)
    {
        $news = News::findOrFail($id);

        if (!$request->filled('slug') && $request->filled('title')) {
            $request->merge([
                'slug' => Str::slug($request->input('title'))
            ]);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:news,slug,' . $news->id,
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'author' => 'required|string|max:255',
            'status' => 'required|in:0,1',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($news->image) {
                Storage::disk('public')->delete($news->image);
            }
            $validated['image'] = $request->file('image')->store('images/news', 'public');
        }

        $news->fill($validated)->save();

        return redirect()->route('bai-viet.index')->with('success', 'Bài viết đã được cập nhật!');
    }

    /**
     * Remove the specified news article.
     */
    public function destroy($id)
    {
        try {
            $news = News::findOrFail($id);

            if ($news->image && Storage::disk('public')->exists($news->image)) {
                Storage::disk('public')->delete($news->image);
            }

            $news->delete();

            return redirect()->route('bai-viet.index')->with('success', 'Xóa bài viết thành công!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi xóa sản phẩm: ' . $e->getMessage());
        }
    }
}
