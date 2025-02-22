<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Rating;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function index()
    {
        return response()->json(Rating::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        return response()->json(Rating::create($request->all()), 201);
    }

    public function show($id)
    {
        return response()->json(Rating::findOrFail($id));
    }

    public function update(Request $request, Rating $rating)
    {
        $request->validate(['rating' => 'integer|min:1|max:5']);
        $rating->update($request->all());
        return response()->json($rating);
    }

    public function destroy(Rating $rating)
    {
        $rating->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }
}
