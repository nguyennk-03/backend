<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class DanhGiaController extends Controller
{
    public function index()
    {
        $reviews = Review::with('user', 'product')->get();
        $products = Product::all();
        return view('admin.reviews.index', compact('reviews','products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        Review::create([
            'user_id' => Auth::id(),
            'product_id' => $request->product_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return redirect()->route('reviews')->with('success', 'Đánh giá đã được thêm!');
    }

    public function edit($id)
    {
        $review = Review::findOrFail($id);
        return view('admin.reviews.edit', compact('review'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:0,1',
        ]);

        $review = Review::findOrFail($id);
        $review->update([
            'status' => $request->status,
        ]);

        return redirect()->route('danh-gia.index')->with('success', 'Trạng thái đánh giá đã được cập nhật!');
    }



    public function destroy($id)
    {
        Review::findOrFail($id)->delete();
        return redirect()->route('reviews')->with('success', 'Đánh giá đã được xóa!');
    }
}
