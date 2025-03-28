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

        if ($request->has('sort_by')) {
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
                default:
                    $query->orderBy('id', 'asc');
                    break;
            }
        } else {
            $query->orderBy('id', 'asc');
        }

        $brands = $query->get();
        return view('admin.brands.index', compact('brands'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'description' => 'nullable|string',
        ]);

        try {
            $validatedData['name'] = trim($validatedData['name']);
            $validatedData['slug'] = Str::slug($validatedData['name']);

            $originalSlug = $validatedData['slug'];
            $count = 1;
            while (Brand::where('slug', $validatedData['slug'])->exists()) {
                $validatedData['slug'] = $originalSlug . '-' . $count++;
            }

            if ($request->hasFile('logo')) {
                $file = $request->file('logo');

                if (!$file->isValid()) {
                    throw new \Exception('File ảnh không hợp lệ: ' . $file->getErrorMessage());
                }

                $publicPath = public_path('images/brands/store');
                if (!file_exists($publicPath)) {
                    mkdir($publicPath, 0755, true);
                }

                $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move($publicPath, $fileName);
                $validatedData['logo'] = 'images/brands/store/' . $fileName;
            }

            Brand::create($validatedData);
            return redirect()->route('thuong-hieu.index')->with('success', 'Thêm thương hiệu thành công');
        } catch (\Exception $e) {
            Log::error('Lỗi khi thêm thương hiệu: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Có lỗi xảy ra khi thêm thương hiệu!'])->withInput();
        }
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'description' => 'nullable|string',
        ]);

        try {
            $brand = Brand::findOrFail($id);
            $validatedData['name'] = trim($validatedData['name']);
            $validatedData['slug'] = Str::slug($validatedData['name']);

            $originalSlug = $validatedData['slug'];
            $count = 1;
            while (Brand::where('slug', $validatedData['slug'])->where('id', '!=', $id)->exists()) {
                $validatedData['slug'] = $originalSlug . '-' . $count++;
            }

            if ($request->hasFile('logo')) {
                $file = $request->file('logo');

                if (!$file->isValid()) {
                    throw new \Exception('File ảnh không hợp lệ: ' . $file->getErrorMessage());
                }

                $publicPath = public_path('images/brands/update');
                if (!file_exists($publicPath)) {
                    mkdir($publicPath, 0755, true);
                }

                $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move($publicPath, $fileName);
                $validatedData['logo'] = 'images/brands/update/' . $fileName;
            }

            $brand->update($validatedData);
            return redirect()->route('thuong-hieu.index')->with('success', 'Cập nhật thương hiệu thành công');
        } catch (\Exception $e) {
            Log::error('Lỗi khi cập nhật thương hiệu: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Có lỗi xảy ra khi cập nhật thương hiệu!']);
        }
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