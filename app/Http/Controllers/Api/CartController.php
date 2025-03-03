<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $query = Cart::with('product');

        // Lọc theo user_id
        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Lọc theo product_id
        if ($request->has('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        // Lọc theo khoảng thời gian
        if ($request->has('from_date') && $request->has('to_date')) {
            $query->whereBetween('created_at', [$request->from_date, $request->to_date]);
        }

        return response()->json($query->get());
    }

    public function show($id)
    {
        $cartItem = Cart::with('product')->find($id);
        return $cartItem
            ? response()->json($cartItem)
            : response()->json(['message' => 'Không tìm thấy sản phẩm trong giỏ hàng!'], 404);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        // Kiểm tra xem sản phẩm đã có trong giỏ hàng chưa
        $existingCart = Cart::where('user_id', $validated['user_id'])
            ->where('product_id', $validated['product_id'])
            ->first();

        if ($existingCart) {
            $existingCart->increment('quantity', $validated['quantity']);
            return response()->json([
                'message' => 'Cập nhật số lượng sản phẩm trong giỏ hàng thành công!',
                'cart' => $existingCart,
            ], 200);
        }

        $cartItem = Cart::create($validated);
        return response()->json([
            'message' => 'Thêm sản phẩm vào giỏ hàng thành công!',
            'cart' => $cartItem,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $cartItem = Cart::find($id);
        if (!$cartItem)
            return response()->json(['message' => 'Không tìm thấy sản phẩm trong giỏ hàng!'], 404);

        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cartItem->update($validated);
        return response()->json([
            'message' => 'Cập nhật giỏ hàng thành công!',
            'cart' => $cartItem,
        ]);
    }

    public function destroy($id)
    {
        $cartItem = Cart::find($id);
        if (!$cartItem)
            return response()->json(['message' => 'Không tìm thấy sản phẩm trong giỏ hàng!'], 404);

        $cartItem->delete();
        return response()->json(['message' => 'Xóa sản phẩm khỏi giỏ hàng thành công!']);
    }
}
