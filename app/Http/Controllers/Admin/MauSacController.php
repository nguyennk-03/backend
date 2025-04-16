<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Color;
use Illuminate\Http\Request;

class MauSacController extends Controller
{
    public function index()
    {
        $colors = Color::all();
        return view('admin.products.color', compact('colors'));
    }

    public function create()
    {
        return view('admin.colors.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:colors,name',
            'code' => 'nullable|string|max:50|unique:colors,code',
            'hex_code' => 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'is_active' => 'nullable|boolean',
        ]);

        $data = $request->only(['name', 'code', 'hex_code']);
        $data['is_active'] = $request->boolean('is_active', true); // mặc định true

        Color::create($data);

        return redirect()->route('mau-sac.index')
            ->with('success', 'Thêm màu sắc thành công!');
    }

    public function show(Color $color)
    {
        return view('admin.colors.show', compact('color'));
    }

    public function edit(Color $color)
    {
        return view('admin.colors.edit', compact('color'));
    }

    public function update(Request $request, $id)
    {
        $color = Color::findOrFail($id);
        $color->is_active = $request->input('is_active') == 1;
        $color->save();

        return back()->with('success', 'Cập nhật trạng thái thành công.');
    }

    public function destroy($id)
    {
        $color = Color::findOrFail($id); 

        $color->delete();

        return redirect()->route('mau-sac.index')
            ->with('success', 'Xóa màu sắc thành công!');
    }
}
