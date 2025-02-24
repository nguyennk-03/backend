<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        return response()->json(Cart::with('product')->get());
    }

    public function show($id)
    {
        $Cart = Cart::with('product')->find($id);
        return $Cart ? response()->json($Cart) : response()->json(['message' => 'Cart Item not found'], 404);
    }

    public function store(Request $request)
    {
        $Cart = Cart::create($request->all());
        return response()->json($Cart, 201);
    }

    public function update(Request $request, $id)
    {
        $Cart = Cart::find($id);
        if (!$Cart) return response()->json(['message' => 'Cart Item not found'], 404);

        $Cart->update($request->all());
        return response()->json($Cart);
    }

    public function destroy($id)
    {
        $Cart = Cart::find($id);
        if (!$Cart) return response()->json(['message' => 'Cart Item not found'], 404);

        $Cart->delete();
        return response()->json(['message' => 'Cart Item deleted']);
    }
}
