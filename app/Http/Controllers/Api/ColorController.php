<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Color;
use Illuminate\Http\Request;

class ColorController extends Controller
{
    public function index(Request $request)
    {
        $query = Color::query();

        if ($request->has('name')) {
            $query->where('name', 'LIKE', '%' . $request->name . '%');
        }

        if ($request->has('in_use')) {
            $inUse = filter_var($request->in_use, FILTER_VALIDATE_BOOLEAN);
            if ($inUse) {
                $query->whereHas('productVariants');
            } else {
                $query->whereDoesntHave('productVariants');
            }
        }

        return response()->json($query->get());
    }


    public function show($id)
    {
        return response()->json(Color::findOrFail($id));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        return response()->json(Color::create($validated), 201);
    }

    public function update(Request $request, $id)
    {
        $color = Color::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $color->update($validated);
        return response()->json($color);
    }

    public function destroy($id)
    {
        $color = Color::findOrFail($id);

        if ($color->productVariants()->exists()) {
            return response()->json(['error' => 'Không thể xóa màu vì đang được sử dụng trong sản phẩm'], 400);
        }

        $color->delete();
        return response()->json(['message' => 'Màu sắc đã được xóa'], 204);
    }
}
