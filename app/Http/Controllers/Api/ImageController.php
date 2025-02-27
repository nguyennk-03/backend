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
        $images = Image::with('productVariant.product')->get();
        return $images ? response()->json($images) : response()->json(['message' => 'Image not found'], 404);
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_variant_id' => 'required|exists:product_variants,id',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images'), $imageName);

            Image::create([
                'product_variant_id' => $request->product_variant_id,
                'image_url' => $imageName, // Chỉ lưu tên file
            ]);
        }

        return response()->json(['message' => 'Image uploaded successfully']);
    }


    public function update(Request $request, $id)
    {
        $image = Image::find($id);
        if (!$image) return response()->json(['message' => 'Image not found'], 404);

        $image->update($request->all());
        return response()->json($image);
    }

    public function destroy($id)
    {
        $image = Image::findOrFail($id);

        // Xóa file trong thư mục public/images/
        $imagePath = public_path('images/' . $image->image_url);
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }

        $image->delete();

        return response()->json(['message' => 'Image deleted successfully']);
    }

}
