<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\News;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $query = News::query();

        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->has('brand_id')) {
            $query->where('brand_id', $request->brand_id);
        }

        $news = $query->orderBy('created_at', 'desc')->paginate(10);

        return response()->json($news);
    }

    public function show($id)
    {
        $news = News::find($id);

        if (!$news) {
            return response()->json(['message' => 'Không tìm thấy bài báo'], 404);
        }

        return response()->json($news);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'image' => 'nullable|url',
            'author' => 'required|string|max:255',
        ]);

        $news = News::create($request->all());

        return response()->json(['message' => 'Tạo bài báo thành công', 'news' => $news], 201);
    }

    public function update(Request $request, $id)
    {
        $news = News::find($id);

        if (!$news) {
            return response()->json(['message' => 'Không tìm thấy bài báo'], 404);
        }

        $request->validate([
            'title' => 'sometimes|string|max:255',
            'content' => 'sometimes',
            'category_id' => 'sometimes|exists:categories,id',
            'brand_id' => 'sometimes|exists:brands,id',
            'image' => 'nullable|url',
            'author' => 'sometimes|string|max:255',
        ]);

        $news->update($request->all());

        return response()->json(['message' => 'Cập nhật bài báo thành công', 'news' => $news]);
    }

    public function destroy($id)
    {
        $news = News::find($id);

        if (!$news) {
            return response()->json(['message' => 'Không tìm thấy bài báo'], 404);
        }

        $news->delete();

        return response()->json(['message' => 'Xóa bài báo thành công']);
    }
}