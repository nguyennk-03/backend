<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Image;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ImageController extends Controller
{
    public function index()
    {
        // Sử dụng với 'product' thay vì 'variant'
        return response()->json(Image::with('product')->get(), Response::HTTP_OK);
    }

    public function show($id)
    {
        // Sử dụng với 'product' thay vì 'variant'
        $image = Image::with('product')->find($id);
        return $image
            ? response()->json($image, Response::HTTP_OK)
            : response()->json(['message' => 'Không tìm thấy hình ảnh!'], Response::HTTP_NOT_FOUND);
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id', // Thay product_variant_id thành product_id
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $imageFile = $request->file('image');
            $imageName = time() . '.' . $imageFile->getClientOriginalExtension();
            $imagePath = 'images/products/' . $imageName;

            // Lưu file vào thư mục public/images/products/
            $imageFile->move(public_path('images/products'), $imageName);

            // Tạo đường dẫn đầy đủ
            $fullImageUrl = asset($imagePath);

            // Lưu vào database
            $image = Image::create([
                'product_id' => $request->product_id, // Thay product_variant_id thành product_id
                'image' => $fullImageUrl,
            ]);

            return response()->json([
                'message' => 'Tải ảnh lên thành công!',
                'data' => $image,
            ], Response::HTTP_CREATED);
        }

        return response()->json(['message' => 'Không có ảnh nào được tải lên!'], Response::HTTP_BAD_REQUEST);
    }

    public function update(Request $request, $id)
    {
        $image = Image::find($id);
        if (!$image) {
            return response()->json(['message' => 'Không tìm thấy hình ảnh!'], Response::HTTP_NOT_FOUND);
        }

        $validated = $request->validate([
            'product_id' => 'sometimes|exists:products,id', // Thay product_variant_id thành product_id
            'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // Xóa ảnh cũ nếu tồn tại
            $oldImagePath = public_path(str_replace(asset('/'), '', $image->image));
            if (file_exists($oldImagePath) && is_file($oldImagePath)) {
                unlink($oldImagePath);
            }

            $imageFile = $request->file('image');
            $imageName = time() . '.' . $imageFile->getClientOriginalExtension();
            $imagePath = 'images/products/' . $imageName;

            // Lưu file mới
            $imageFile->move(public_path('images/products'), $imageName);

            // Cập nhật đường dẫn ảnh mới
            $validated['image'] = asset($imagePath);
        }

        $image->update($validated);
        return response()->json([
            'message' => 'Cập nhật ảnh thành công!',
            'data' => $image,
        ], Response::HTTP_OK);
    }

    public function destroy($id)
    {
        $image = Image::find($id);
        if (!$image) {
            return response()->json(['message' => 'Không tìm thấy hình ảnh!'], Response::HTTP_NOT_FOUND);
        }

        // Kiểm tra và xóa file ảnh
        $imagePath = public_path(str_replace(asset('/'), '', $image->image));
        if (file_exists($imagePath) && is_file($imagePath)) {
            unlink($imagePath);
        }

        $image->delete();

        return response()->json(['message' => 'Xóa ảnh thành công!'], Response::HTTP_OK);
    }
}
