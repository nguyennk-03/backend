<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Color;
use Illuminate\Http\Request;

class MauSacController extends Controller
{
    /**
     * Hiển thị danh sách màu sắc
     */
    public function index()
    {
        $colors = Color::paginate(10);
        return view('admin.colors.index', compact('colors'));
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
            'name' => 'required|string|max:255|unique:mau_sac,name',
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
    public function show(Color $Color)
    {
        return view('admin.colors.show', compact('Color'));
    }

    /**
     * Hiển thị form chỉnh sửa màu sắc
     */
    public function edit(Color $Color)
    {
        return view('admin.colors.edit', compact('Color'));
    }

    /**
     * Cập nhật màu sắc trong database
     */
    public function update(Request $request, Color $Color)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:mau_sac,name,' . $Color->id,
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only('name');
        
        if ($request->hasFile('image')) {
            // Xóa ảnh cũ nếu tồn tại
            if ($Color->image) {
                Storage::disk('public')->delete($Color->image);
            }
            
            $imagePath = $request->file('image')->store('colors', 'public');
            $data['image'] = $imagePath;
        }

        $Color->update($data);

        return redirect()->route('mau-sac.index')
            ->with('success', 'Cập nhật màu sắc thành công!');
    }

    /**
     * Xóa màu sắc
     */
    public function destroy(Color $Color)
    {
        // Xóa ảnh nếu tồn tại
        if ($Color->image) {
            Storage::disk('public')->delete($Color->image);
        }
        
        $Color->delete();

        return redirect()->route('mau-sac.index')
            ->with('success', 'Xóa màu sắc thành công!');
    }
}
