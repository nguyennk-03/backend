<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;

class WishlistController extends Controller
{
    /**
     * Hiển thị danh sách wishlist của người dùng
     */
    public function index()
    {
        $wishlists = Wishlist::all();
        return response()->json([
            'success' => true,
            'data' => $wishlists
        ], Response::HTTP_OK);
    }

    /**
     * Lưu sản phẩm vào danh sách yêu thích
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'product_id' => 'required|exists:products,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $wishlist = Wishlist::create([
            'user_id' => $request->user_id,
            'product_id' => $request->product_id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Sản phẩm đã được thêm vào wishlist!',
            'data' => $wishlist
        ], Response::HTTP_CREATED);
    }

    /**
     * Hiển thị chi tiết một wishlist item
     */
    public function show($id)
    {
        $wishlist = Wishlist::find($id);

        if (!$wishlist) {
            return response()->json([
                'success' => false,
                'message' => 'Sản phẩm không tồn tại trong wishlist!'
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'success' => true,
            'data' => $wishlist
        ], Response::HTTP_OK);
    }

    /**
     * Xóa sản phẩm khỏi danh sách yêu thích
     */
    public function destroy($id)
    {
        $wishlist = Wishlist::find($id);

        if (!$wishlist) {
            return response()->json([
                'success' => false,
                'message' => 'Sản phẩm không tồn tại trong wishlist!'
            ], Response::HTTP_NOT_FOUND);
        }

        $wishlist->delete();

        return response()->json([
            'success' => true,
            'message' => 'Sản phẩm đã được xóa khỏi wishlist!'
        ], Response::HTTP_OK);
    }
}
