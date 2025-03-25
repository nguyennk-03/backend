<?php

namespace App\Http\Controllers\Admin;

use App\Models\Brand;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ThuongHieuController extends Controller
{
    public function index()
    {
        $brands = Brand::all();
        return view('admin.brands.index', compact('brands'));
    }

    public function store(Request $request)
    {
        Brand::create($request->all());
        return redirect()->route('brands')->with('success', 'Thêm thương hiệu thành công');
    }

    public function edit($id)
    {
        $brand = Brand::findOrFail($id);
        return view('admin.brands.edit', compact('brand'));
    }

    public function update(Request $request, $id)
    {
        Brand::findOrFail($id)->update($request->all());
        return redirect()->route('brands')->with('success', 'Cập nhật thương hiệu thành công');
    }

    public function destroy($id)
    {
        Brand::destroy($id);
        return redirect()->route('brands')->with('success', 'Xóa thương hiệu thành công');
    }
}
