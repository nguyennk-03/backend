<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Discount;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class GiamGiaController extends Controller
{
    public function index(Request $request)
    {
        // Fetch discounts with filters
        $query = Discount::query();

        if ($request->has('is_active') && $request->is_active !== '') {
            $query->where('is_active', $request->is_active);
        }
        if ($request->has('discount_type') && $request->discount_type !== '') {
            $query->where('discount_type', $request->discount_type);
        }
        if ($request->has('start_date') && $request->start_date) {
            $query->where('start_date', '>=', $request->start_date);
        }
        if ($request->has('end_date') && $request->end_date) {
            $query->where('end_date', '<=', $request->end_date);
        }

        $discounts = $query->orderBy('id', 'desc')->get();

        return view('admin.discounts.index', compact('discounts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:discounts',
            'discount_type' => 'required|in:0,1',
            'value' => 'required|numeric|min:0',
            'min_order_amount' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'is_active' => 'required|boolean',
            'usage_limit' => 'nullable|integer|min:0',
        ]);

        Discount::create($request->only([
            'name',
            'code',
            'discount_type',
            'value',
            'min_order_amount',
            'start_date',
            'end_date',
            'is_active',
            'usage_limit'
        ]));

        return redirect()->route('giam-gia.index')->with('success', 'Thêm mã giảm giá thành công!');
    }

    public function update(Request $request, $id)
    {
        $discount = Discount::findOrFail($id);

        // Cập nhật thông tin mã giảm giá
        $discount->fill($request->only([
            'name',
            'code',
            'discount_type',
            'value',
            'min_order_amount',
            'start_date',
            'end_date',
            'is_active',
            'usage_limit',
            'used_count', // thêm trường này vào nếu bạn muốn cập nhật 'used_count'
        ]));

        // Kiểm tra nếu có thay đổi trước khi lưu
        if ($discount->isDirty()) {
            $discount->save();
            return redirect()->route('giam-gia.index')->with('success', 'Cập nhật mã giảm giá thành công!');
        } else {
            return redirect()->route('giam-gia.index')->with('info', 'Không có thay đổi nào để cập nhật.');
        }
    }


    public function destroy($id)
    {
        try {
            $discount = Discount::findOrFail($id);

            $discount->delete();
            return redirect()->route('giam-gia.index')->with('success', 'Xóa thương hiệu thành công');
        } catch (\Exception $e) {
            log::error('Lỗi khi xóa mã giảm giá: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Có lỗi xảy ra khi xóa thương hiệu!']);
        }
    }
}
