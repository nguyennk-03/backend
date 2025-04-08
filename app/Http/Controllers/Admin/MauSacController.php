<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Color;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MauSacController extends Controller
{
    /**
     * Hiển thị danh sách màu sắc
     */
    public function index()
    {
        $colors = Color::all();
        return view('admin.products.color', compact('colors'));
    }

    /**
     * Hiển thị form thêm màu sắc
     */
    public function create()
    {
        return view('admin.colors.create');
    }

    /**
     * Lưu màu sắc mới vào database
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:colors,name',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only('name');

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('colors', 'public');
            $data['image'] = $imagePath;
        }

        Color::create($data);

        return redirect()->route('mau-sac.index')
            ->with('success', 'Thêm màu sắc thành công!');
    }

    /**
     * Hiển thị thông tin chi tiết màu sắc
     */
    public function show(Color $color)
    {
        return view('admin.colors.show', compact('color'));
    }

    /**
     * Hiển thị form chỉnh sửa màu sắc
     */
    public function edit(Color $color)
    {
        return view('admin.colors.edit', compact('color'));
    }

    /**
     * Cập nhật màu sắc trong database
     */
    public function update(Request $request, Color $color)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:colors,name,' . $color->id,
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only('name');

        if ($request->hasFile('image')) {
            // Xóa ảnh cũ nếu có
            if ($color->image) {
                Storage::disk('public')->delete($color->image);
            }

            $imagePath = $request->file('image')->store('colors', 'public');
            $data['image'] = $imagePath;
        }

        $color->update($data);

        return redirect()->route('mau-sac.index')
            ->with('success', 'Cập nhật màu sắc thành công!');
    }

    /**
     * Xóa màu sắc
     */
    public function destroy(Color $color)
    {
        if ($color->image) {
            Storage::disk('public')->delete($color->image);
        }

        $color->delete();

        return redirect()->route('mau-sac.index')
            ->with('success', 'Xóa màu sắc thành công!');
    }
}
