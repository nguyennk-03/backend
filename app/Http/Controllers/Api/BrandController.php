<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    // Lấy danh sách thương hiệu có thể lọc theo tên và slug
    public function index(Request $request)
    {
        $query = Brand::query();

        if ($request->has('name')) {
            $query->where('name', 'LIKE', '%' . $request->name . '%');
        }

        if ($request->has('slug')) {
            $query->where('slug', 'LIKE', '%' . $request->slug . '%');
        }

        return response()->json($query->get());
    }

    // Lấy chi tiết một thương hiệu
    public function show($id)
    {
        $brand = Brand::find($id);
        return $brand
            ? response()->json($brand)
            : response()->json(['message' => 'Không tìm thấy thương hiệu!'], 404);
    }

    // Tạo mới một thương hiệu
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:brands,slug',
            'logo' => 'nullable|string|max:255',
        ]);

        $brand = Brand::create($validated);
        return response()->json([
            'message' => 'Thương hiệu được tạo thành công!',
            'data' => $brand
        ], 201);
    }

    // Cập nhật một thương hiệu
    public function update(Request $request, $id)
    {
        $brand = Brand::find($id);
        if (!$brand)
            return response()->json(['message' => 'Không tìm thấy thương hiệu!'], 404);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:brands,slug,' . $id,
            'logo' => 'nullable|string|max:255',
        ]);

        $brand->update($validated);
        return response()->json([
            'message' => 'Cập nhật thương hiệu thành công!',
            'data' => $brand
        ]);
    }

    // Xóa một thương hiệu
    public function destroy($id)
    {
        $brand = Brand::find($id);
        if (!$brand)
            return response()->json(['message' => 'Không tìm thấy thương hiệu!'], 404);

        $brand->delete();
        return response()->json(['message' => 'Xóa thương hiệu thành công!']);
    }
}
