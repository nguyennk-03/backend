<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Người dùng chưa đăng nhập!',
            ], 401);
        }

        $carts = Cart::with(['variant.product'])
            ->where('user_id', $user->id)
            ->get();

        return response()->json([
            'status' => true,
            'message' => 'Lấy danh sách giỏ hàng thành công!',
            'cart' => $carts,
        ]);
    }

    public function show($id)
    {
        $cartItem = Cart::with('variant.product')
            ->where('id', $id)
            ->where('user_id', auth()->id())
            ->first();

        if (!$cartItem) {
            return response()->json(['message' => 'Không tìm thấy sản phẩm trong giỏ hàng!'], 404);
        }

        return response()->json($cartItem);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'variant_id' => 'required|exists:product_variants,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $userId = auth()->id();
        if (!$userId) {
            return response()->json(['message' => 'Người dùng chưa đăng nhập!'], 401);
        }

        $variant = ProductVariant::findOrFail($validated['variant_id']);
        $totalPrice = $variant->price * $validated['quantity'];

        $existingCart = Cart::where('user_id', $userId)
            ->where('variant_id', $validated['variant_id'])
            ->first();

        if ($existingCart) {
            $existingCart->quantity += $validated['quantity'];
            $existingCart->total_price += $totalPrice;
            $existingCart->save();

            return response()->json([
                'message' => 'Cập nhật số lượng sản phẩm trong giỏ hàng thành công!',
                'cart' => $existingCart,
            ], 200);
        }

        $cartItem = Cart::create([
            'user_id' => $userId,
            'variant_id' => $validated['variant_id'],
            'quantity' => $validated['quantity'],
            'total_price' => $totalPrice,
        ]);

        return response()->json([
            'message' => 'Thêm sản phẩm vào giỏ hàng thành công!',
            'cart' => $cartItem,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $cartItem = Cart::where('id', $id)
            ->where('user_id', auth()->id())
            ->first();

        if (!$cartItem) {
            return response()->json(['message' => 'Không tìm thấy sản phẩm trong giỏ hàng!'], 404);
        }

        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $variant = ProductVariant::find($cartItem->variant_id);
        if (!$variant) {
            return response()->json(['message' => 'Không tìm thấy biến thể sản phẩm!'], 404);
        }

        $cartItem->update([
            'quantity' => $validated['quantity'],
            'total_price' => $variant->price * $validated['quantity'],
        ]);

        return response()->json([
            'message' => 'Cập nhật giỏ hàng thành công!',
            'cart' => $cartItem,
        ]);
    }

    public function destroy($id)
    {
        $cartItem = Cart::where('id', $id)
            ->where('user_id', auth()->id())
            ->first();

        if (!$cartItem) {
            return response()->json(['message' => 'Không tìm thấy sản phẩm trong giỏ hàng!'], 404);
        }

        $cartItem->delete();

        return response()->json(['message' => 'Xóa sản phẩm khỏi giỏ hàng thành công!'], 200);
    }
}
