<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OrderItem;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OrderItemController extends Controller
{
    public function index()
    {
        $orderItems = OrderItem::with([
            'product:id,name,stock_quantity,sold',
            'order:id,code,status,total_price' // thêm dòng này
        ])
            ->whereHas('order', function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->select('id', 'order_id', 'product_id', 'quantity', 'price','image')
            ->latest()
            ->get();

        return response()->json($orderItems, Response::HTTP_OK);
    }

    public function show($id)
    {
        $orderItem = OrderItem::with('product')->findOrFail($id);
        return response()->json($orderItem, Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0', // Giá gửi từ client là giá gốc (VD: 2800000)
        ]);

        $order = Order::findOrFail($validated['order_id']);
        if ($order->status !== 0) { // 0: Chờ xử lý (theo schema: 0 là PENDING)
            return response()->json([
                'message' => 'Không thể thêm sản phẩm vào đơn hàng đã xử lý'
            ], Response::HTTP_BAD_REQUEST);
        }

        // Kiểm tra số lượng tồn kho
        $product = Product::findOrFail($validated['product_id']);
        if ($product->stock_quantity < $validated['quantity']) {
            return response()->json([
                'message' => "Sản phẩm ID {$product->id} không đủ hàng (còn: {$product->stock_quantity})."
            ], Response::HTTP_BAD_REQUEST);
        }

        // Chia giá cho 100 để lưu dưới dạng decimal(10, 2)
        $validated['price'] = $validated['price'] / 100;

        $orderItem = OrderItem::create($validated);

        // Cập nhật số lượng tồn kho và số lượng đã bán
        $product->decrement('stock_quantity', $validated['quantity']);
        $product->increment('sold', $validated['quantity']);

        return response()->json($orderItem, Response::HTTP_CREATED);
    }

    public function update(Request $request, $id)
    {
        $orderItem = OrderItem::findOrFail($id);

        $validated = $request->validate([
            'quantity' => 'sometimes|integer|min:1',
            'price' => 'sometimes|numeric|min:0', // Giá gửi từ client là giá gốc (VD: 2800000)
        ]);

        if ($orderItem->order->status !== 0) { // 0: Chờ xử lý (theo schema: 0 là PENDING)
            return response()->json([
                'message' => 'Không thể chỉnh sửa sản phẩm trong đơn hàng đã xử lý'
            ], Response::HTTP_BAD_REQUEST);
        }

        $product = Product::findOrFail($orderItem->product_id);

        // Nếu cập nhật số lượng, kiểm tra và điều chỉnh tồn kho
        if (isset($validated['quantity'])) {
            $quantityDifference = $validated['quantity'] - $orderItem->quantity;

            if ($quantityDifference > 0 && $product->stock_quantity < $quantityDifference) {
                return response()->json([
                    'message' => "Sản phẩm ID {$product->id} không đủ hàng (còn: {$product->stock_quantity})."
                ], Response::HTTP_BAD_REQUEST);
            }

            // Cập nhật số lượng tồn kho và số lượng đã bán
            if ($quantityDifference != 0) {
                $product->decrement('stock_quantity', $quantityDifference);
                $product->increment('sold', $quantityDifference);
            }
        }

        // Nếu cập nhật giá, chia giá cho 100 để lưu dưới dạng decimal(10, 2)
        if (isset($validated['price'])) {
            $validated['price'] = $validated['price'] / 100;
        }

        $orderItem->update($validated);
        return response()->json($orderItem, Response::HTTP_OK);
    }

    public function destroy($id)
    {
        $orderItem = OrderItem::findOrFail($id);

        if ($orderItem->order->status !== 0) { // 0: Chờ xử lý (theo schema: 0 là PENDING)
            return response()->json([
                'message' => 'Không thể xóa sản phẩm trong đơn hàng đã xử lý'
            ], Response::HTTP_BAD_REQUEST);
        }

        $product = Product::findOrFail($orderItem->product_id);

        // Hoàn lại số lượng tồn kho và điều chỉnh số lượng đã bán
        $product->increment('stock_quantity', $orderItem->quantity);
        $product->decrement('sold', $orderItem->quantity);

        $orderItem->delete();
        return response()->json([
            'message' => 'Order Item deleted'
        ], Response::HTTP_OK);
    }
}
