<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use Illuminate\Http\Request;
use App\Models\ProductVariant;
class CartController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->user()) {
            return response()->json([
                'status' => false,
                'message' => 'Người dùng chưa đăng nhập!',
            ], 401);
        }

        $userId = $request->user()->id;

        $carts = Cart::with(['productVariant.product'])
            ->where('user_id', $userId)
            ->get();

        return response()->json([
            'status' => true,
            'message' => 'Lấy danh sách giỏ hàng thành công!',
            'cart' => $carts,
        ]);
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
            'variant_id' => 'required|exists:product_variants,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $variant = ProductVariant::findOrFail($validated['variant_id']);

        $totalPrice = $variant->price * $validated['quantity'];

        $existingCart = Cart::where('user_id', $validated['user_id'])
            ->where('variant_id', $validated['variant_id'])
            ->first();

        if ($existingCart) {
            $existingCart->increment('quantity', $validated['quantity']);
            $existingCart->increment('total_price', $totalPrice);
            return response()->json([
                'message' => 'Cập nhật số lượng sản phẩm trong giỏ hàng thành công!',
                'cart' => $existingCart,
            ], 200);
        }

        $cartItem = Cart::create([
            'user_id' => $validated['user_id'],
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
        $cartItem = Cart::find($id);
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

        $totalPrice = $variant->price * $validated['quantity'];

        $cartItem->update([
            'quantity' => $validated['quantity'],
            'total_price' => $totalPrice,
        ]);

        return response()->json([
            'message' => 'Cập nhật giỏ hàng thành công!',
            'cart' => $cartItem,
        ]);
    }


    public function destroy($id)
    {
        $cartItem = Cart::where([
            ['id', '=', $id],
            ['user_id', '=', auth()->user()->id]
        ])->first();

        if (!$cartItem) {
            return response()->json(['message' => 'Không tìm thấy sản phẩm trong giỏ hàng!'], 404);
        }

        $cartItem->delete();

        return response()->json(['message' => 'Xóa sản phẩm khỏi giỏ hàng thành công!'], 200);
    }


}