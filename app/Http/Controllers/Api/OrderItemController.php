<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OrderItem;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderItemController extends Controller
{
    public function index()
    {
        $orderItems = OrderItem::with('productVariant')->get();
        return response()->json($orderItems);
    }

    public function show($id)
    {
        $orderItem = OrderItem::with('productVariant')->findOrFail($id);
        return response()->json($orderItem);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'product_variant_id' => 'required|exists:product_variants,id',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
        ]);

        $order = Order::findOrFail($validated['order_id']);
        if ($order->status !== 'pending') {
            return response()->json(['message' => 'Không thể thêm sản phẩm vào đơn hàng đã xử lý'], 400);
        }

        $orderItem = OrderItem::create($validated);
        return response()->json($orderItem, 201);
    }

    public function update(Request $request, $id)
    {
        $orderItem = OrderItem::findOrFail($id);

        $validated = $request->validate([
            'quantity' => 'sometimes|integer|min:1',
            'price' => 'sometimes|numeric|min:0',
        ]);

        if ($orderItem->order->status !== 'pending') {
            return response()->json(['message' => 'Không thể chỉnh sửa sản phẩm trong đơn hàng đã xử lý'], 400);
        }

        $orderItem->update($validated);
        return response()->json($orderItem);
    }

    public function destroy($id)
    {
        $orderItem = OrderItem::findOrFail($id);

        if ($orderItem->order->status !== 'pending') {
            return response()->json(['message' => 'Không thể xóa sản phẩm trong đơn hàng đã xử lý'], 400);
        }

        $orderItem->delete();
        return response()->json(['message' => 'Order Item deleted']);
    }
}
