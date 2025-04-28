<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Size;
use Illuminate\Http\Request;

class SizeController extends Controller
{
    // Display a listing of the sizes
    public function index()
    {
        $sizes = Size::all(); // Get all sizes from the database
        return response()->json($sizes);
    }

    // Store a newly created size in storage
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $size = Size::create([
            'name' => $request->name,
        ]);

        return response()->json($size, 201); // Return the created size
    }

    // Display the specified size
    public function show($id)
    {
        $size = Size::findOrFail($id); // Find size by ID
        return response()->json($size);
    }

    // Update the specified size in storage
    public function update(Request $request, $id)
    {
        $size = Size::findOrFail($id); // Find the size by ID
        $size->update([
            'name' => $request->name,
        ]);

        return response()->json($size); // Return updated size
    }

    // Remove the specified size from storage
    public function destroy($id)
    {
        $size = Size::findOrFail($id); // Find the size by ID
        $size->delete(); // Delete the size

        return response()->json(null, 204); // Return a success response
    }
}
