<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use Illuminate\Http\Request;

class CartItemController extends Controller
{
    public function index()
    {
        return response()->json(CartItem::with('product')->get());
    }

    public function show($id)
    {
        $cartItem = CartItem::with('product')->find($id);
        return $cartItem ? response()->json($cartItem) : response()->json(['message' => 'Cart Item not found'], 404);
    }

    public function store(Request $request)
    {
        $cartItem = CartItem::create($request->all());
        return response()->json($cartItem, 201);
    }

    public function update(Request $request, $id)
    {
        $cartItem = CartItem::find($id);
        if (!$cartItem) return response()->json(['message' => 'Cart Item not found'], 404);

        $cartItem->update($request->all());
        return response()->json($cartItem);
    }

    public function destroy($id)
    {
        $cartItem = CartItem::find($id);
        if (!$cartItem) return response()->json(['message' => 'Cart Item not found'], 404);

        $cartItem->delete();
        return response()->json(['message' => 'Cart Item deleted']);
    }
}
