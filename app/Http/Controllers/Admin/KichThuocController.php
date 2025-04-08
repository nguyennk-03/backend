<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Size;
use Illuminate\Http\Request;

class KichThuocController extends Controller
{
    // Danh sách size
    public function index()
    {
        $sizes = Size::orderBy('name')->get();
        return view('admin.products.size', compact('sizes'));
    }

    // Hiển thị form thêm size
    public function create()
    {
        return view('sizes.create');
    }

    // Lưu size mới
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:sizes,name|max:10',
            'cm' => 'required|numeric|min:10|max:35',
        ]);

        Size::create($request->only(['name', 'cm']));

        return redirect()->route('admin.products.size')->with('success', 'Đã thêm size thành công.');
    }

    // Hiển thị form sửa size
    public function edit($id)
    {
        $size = Size::findOrFail($id);
        return view('sizes.edit', compact('size'));
    }

    // Cập nhật size
    public function update(Request $request, $id)
    {
        $size = Size::findOrFail($id);

        $request->validate([
            'name' => 'required|max:10|unique:sizes,name,' . $id,
            'cm' => 'required|numeric|min:10|max:35',
        ]);

        $size->update($request->only(['name', 'cm']));

        return redirect()->route('admin.products.size')->with('success', 'Cập nhật size thành công.');
    }

    // Xoá size
    public function destroy($id)
    {
        $size = Size::findOrFail($id);
        $size->delete();

        return redirect()->route('admin.products.size')->with('success', 'Đã xoá size.');
    }
}
