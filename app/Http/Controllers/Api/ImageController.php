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
        $image = Image::create($request->all());
        return response()->json($image, 201);
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
        $image = Image::find($id);
        if (!$image) return response()->json(['message' => 'Image not found'], 404);

        $image->delete();
        return response()->json(['message' => 'Image deleted']);
    }
}
