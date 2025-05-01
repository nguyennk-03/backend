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
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'required|string',
                'image' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
                'category_id' => 'required|exists:categories,id',
                'brand_id' => 'nullable|exists:brands,id',
                'author' => 'required|string|max:255',
                'status' => 'required|in:0,1',
            ]);

            $data = $request->only('title', 'content', 'category_id', 'brand_id', 'author', 'status');
            $data['slug'] = Str::slug($request->title);

            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('news', 'public');
            }

            News::create($data);

            return redirect()->route('bai-viet.index')->with('success', 'Bài viết đã được thêm!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Đã có lỗi xảy ra: ' . $e->getMessage());
        }
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
    public function update(Request $request, News $news)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'required|string',
                'image' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
                'category_id' => 'required|exists:categories,id',
                'brand_id' => 'nullable|exists:brands,id',
                'author' => 'required|string|max:255',
                'status' => 'required|in:0,1',
            ]);

            $data = $request->only('title', 'content', 'category_id', 'brand_id', 'author', 'status');
            $data['slug'] = Str::slug($request->title);

            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($news->image) {
                    Storage::disk('public')->delete($news->image);
                }
                $data['image'] = $request->file('image')->store('news', 'public');
            }

            $news->update($data);

            return redirect()->route('bai-viet.index')->with('success', 'Bài viết đã được cập nhật!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Đã có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified news article.
     */
    public function destroy(News $news)
    {
        try {
            // Delete associated image if exists
            if ($news->image) {
                Storage::disk('public')->delete($news->image);
            }

            $news->delete();
            return redirect()->route('bai-viet.index')->with('success', 'Bài viết đã được xóa!');
        } catch (\Exception $e) {
            return back()->with('error', 'Đã có lỗi xảy ra: ' . $e->getMessage());
        }
    }
}
