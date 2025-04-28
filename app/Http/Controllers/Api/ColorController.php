<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Color;
use Illuminate\Http\Request;

class ColorController extends Controller
{
    // Display a listing of the colors
    public function index()
    {
        $colors = Color::all(); // Get all colors from the database
        return response()->json($colors);
    }

    // Store a newly created color in storage
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'hex_code' => 'required|string|max:7', // Assuming hex code is a string of 7 characters (e.g., #FFFFFF)
            'is_active' => 'boolean', // Optional, default is true
        ]);

        $color = Color::create([
            'name' => $request->name,
            'hex_code' => $request->hex_code,
            'is_active' => $request->is_active ?? true, // Default to true if not provided
        ])->makeHidden(['created_at', 'updated_at']); // Hide timestamps if not needed

        return response()->json($color, 201); // Return the created color
    }

    // Display the specified color
    public function show($id)
    {
        $color = Color::findOrFail($id); // Find color by ID
        return response()->json($color);
    }

    // Update the specified color in storage
    public function update(Request $request, $id)
    {
        $color = Color::findOrFail($id); // Find the color by ID
        $color->update([
            'name' => $request->name,
            'hex_code' => $request->hex_code,
            'is_active' => $request->is_active ?? $color->is_active, // Keep current value if not provided
        ])->makeHidden(['created_at', 'updated_at'
        ])->makeVisible(['updated_at']); // Show updated_at if needed

        return response()->json($color); // Return updated color
    }

    // Remove the specified color from storage
    public function destroy($id)
    {
        $color = Color::findOrFail($id); // Find the color by ID
        $color->delete(); // Delete the color

        return response()->json(null, 204); // Return a success response
    }
}
