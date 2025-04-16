<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Size;
use Illuminate\Http\Request;

class KichThuocController extends Controller
{
    /**
     * Hiển thị danh sách size
     */
    public function index()
    {
        $sizes = Size::all();
        return view('admin.products.size', compact('sizes'));
    }

    /**
     * Lưu size mới vào database
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|numeric|unique:sizes,name|between:30,50',
            'cm'   => 'nullable|numeric|between:18,35',
        ]);

        Size::create([
            'name' => $request->name,
            'cm'   => $request->cm,
        ]);

        return redirect()->route('kich-thuoc.index')->with('success', 'Đã thêm size thành công.');
    }

    /**
     * Hiển thị form chỉnh sửa size (chưa dùng nếu không có blade edit riêng)
     */
    public function edit($id)
    {
        $size = Size::findOrFail($id);
        return view('admin.products.size-edit', compact('size')); // nếu dùng modal hoặc không có thì có thể bỏ
    }

    /**
     * Cập nhật size trong database
     */
    public function update(Request $request, $id)
    {
        $size = Size::findOrFail($id);
        $size->is_active = $request->input('is_active') == 1;
        $size->save();

        return back()->with('success', 'Cập nhật trạng thái thành công.');
    }

    /**
     * Xóa size
     */
    public function destroy($id)
    {
        $size = Size::findOrFail($id);
        $size->delete();

        return redirect()->route('kich-thuoc.index')->with('success', 'Đã xoá size thành công.');
    }
}
