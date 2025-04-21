<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Size;
use Illuminate\Http\Request;

class SizeController extends Controller
{
    // Lấy danh sách kích thước với phân trang
    public function index(Request $request)
    {
        $query = Size::query();

        if ($request->has('size')) {
            $query->where('size', 'like', '%' . $request->size . '%');
        }

        return response()->json($query->orderBy('size')->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'size' => 'required|string|unique:sizes,size|max:50',
        ]);

        $size = Size::create($validated);

        return response()->json($size->only(['id', 'size']), 201);
    }

    public function update(Request $request, $id)
    {
        $size = Size::findOrFail($id);

        $validated = $request->validate([
            'size' => 'required|string|unique:sizes,size,' . $size->id . '|max:50',
        ]);

        $size->update($validated);

        return response()->json($size->only(['id', 'size']));
    }

    public function destroy($id)
    {
        try {
            $size = Size::findOrFail($id);

            if ($size->products()->count() > 0) {
                return response()->json(['error' => 'Không thể xóa kích thước này vì đang được sử dụng.'], 400);
            }

            $size->delete();

            return response()->json(['message' => 'Kích thước đã được xóa.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Đã xảy ra lỗi khi xóa kích thước.'], 500);
        }
    }
}
