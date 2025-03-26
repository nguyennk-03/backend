<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Image;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    public function index()
    {
        return response()->json(Image::with('product')->get());
    }

    public function show($id)
    {
        $image = Image::with('productVariant.product')->find($id);
        return $image
            ? response()->json($image)
            : response()->json(['message' => 'Không tìm thấy hình ảnh!'], 404);
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_variant_id' => 'required|exists:product_variants,id',
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
                'product_variant_id' => $request->product_variant_id,
                'image' => $fullImageUrl,
            ]);

            return response()->json([
                'message' => 'Tải ảnh lên thành công!',
                'data' => $image,
            ], 201);
        }

        return response()->json(['message' => 'Không có ảnh nào được tải lên!'], 400);
    }

    public function update(Request $request, $id)
    {
        $image = Image::find($id);
        if (!$image)
            return response()->json(['message' => 'Không tìm thấy hình ảnh!'], 404);

        $image->update($request->all());
        return response()->json([
            'message' => 'Cập nhật ảnh thành công!',
            'data' => $image,
        ]);
    }

    public function destroy($id)
    {
        $image = Image::find($id);
        if (!$image)
            return response()->json(['message' => 'Không tìm thấy hình ảnh!'], 404);

        // Kiểm tra và xóa file ảnh
        $imagePath = public_path(str_replace(asset('/'), '', $image->image));
        if (file_exists($imagePath) && is_file($imagePath)) {
            unlink($imagePath);
        }

        $image->delete();

        return response()->json(['message' => 'Xóa ảnh thành công!']);
    }
}
