<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class OrderItemController extends Controller
{
    public function index()
    {
        return response()->json(OrderItem::with('productVariant')->get());
    }

    public function show($id)
    {
        $orderItem = OrderItem::with('productVariant')->find($id);
        return $orderItem ? response()->json($orderItem) : response()->json(['message' => 'Order Item not found'], 404);
    }

    public function store(Request $request)
    {
        $orderItem = OrderItem::create($request->all());
        return response()->json($orderItem, 201);
    }

    public function update(Request $request, $id)
    {
        $orderItem = OrderItem::find($id);
        if (!$orderItem) return response()->json(['message' => 'Order Item not found'], 404);

        $orderItem->update($request->all());
        return response()->json($orderItem);
    }

    public function destroy($id)
    {
        $orderItem = OrderItem::find($id);
        if (!$orderItem) return response()->json(['message' => 'Order Item not found'], 404);

        $orderItem->delete();
        return response()->json(['message' => 'Order Item deleted']);
    }
}
