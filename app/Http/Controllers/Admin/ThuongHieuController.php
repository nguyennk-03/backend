<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ThuongHieuController extends Controller
{
    public function index(Request $request)
    {
        $query = Brand::query();
        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->sort_by) {
            switch ($request->sort_by) {
                case 'name_asc':
                    $query->orderBy('name', 'asc');
                    break;
                case 'name_desc':
                    $query->orderBy('name', 'desc');
                    break;
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'oldest':
                    $query->orderBy('created_at', 'asc');
                    break;
            }
        }

        $brands = $query->get(); 
        return view('admin.brands.index', compact('brands'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'required|boolean',
        ]);

        $data = $validated;

        // Đảm bảo tên thương hiệu là duy nhất
        $originalName = trim($data['name']);
        $count = 1;
        while (Brand::where('name', $data['name'])->exists()) {
            $data['name'] = $originalName . '-' . $count++;
        }

        if ($request->hasFile('logo')) {
            // Lưu ảnh vào storage/app/public/images/brands
            // và chỉ lưu đường dẫn tương đối để dùng cho truy cập qua public/storage
            $path = $request->file('logo')->store('images/brands', 'public');
            $data['logo'] = $path; // ví dụ: 'images/brands/abc.jpg'
        }

        Brand::create($data);

        return redirect()->route('thuong-hieu.index')->with('success', 'Thương hiệu đã được thêm thành công!');
    }


    public function update(Request $request, $id)
    {
        // Tìm thương hiệu theo ID
        $brand = Brand::findOrFail($id);

        // Cập nhật thông tin thương hiệu (ngoại trừ logo)
        $brand->fill($request->except('logo'));

        if ($request->hasFile('logo')) {
            // Xoá logo cũ nếu có
            if ($brand->logo && Storage::disk('public')->exists($brand->logo)) {
                Storage::disk('public')->delete($brand->logo);
            }

            // Lưu logo mới vào storage/app/public/images/brands
            $path = $request->file('logo')->store('images/brands', 'public');
            $brand->logo = $path;
        }

        $brand->save();

        return redirect()->route('thuong-hieu.index')->with('success', 'Cập nhật thương hiệu thành công!');
    }


    public function destroy($id)
    {
        try {
            $brand = Brand::findOrFail($id);

            if ($brand->logo) {
                Storage::disk('public')->delete($brand->logo);
            }

            $brand->delete();
            return redirect()->route('thuong-hieu.index')->with('success', 'Xóa thương hiệu thành công');
        } catch (\Exception $e) {
            Log::error('Lỗi khi xóa thương hiệu: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Có lỗi xảy ra khi xóa thương hiệu!']);
        }
    }
}
