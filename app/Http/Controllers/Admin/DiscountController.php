<?php

namespace App\Http\Controllers\Admin;

use App\Models\Discount;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    public function index()
    {
        $discounts = Discount::all(     );
        return view('admin.discounts.index', compact('discounts'));
    }

    public function store(Request $request)
    {
        Discount::create($request->all());
        return redirect()->route('discounts')->with('success', 'Thêm mã giảm giá thành công');
    }

    public function edit($id)
    {
        $discount = Discount::findOrFail($id);
        return view('admin.discounts.edit', compact('discount'));
    }

    public function update(Request $request, $id)
    {
        Discount::findOrFail($id)->update($request->all());
        return redirect()->route('discounts')->with('success', 'Cập nhật mã giảm giá thành công');
    }

    public function destroy($id)
    {
        Discount::destroy($id);
        return redirect()->route('discounts')->with('success', 'Xóa mã giảm giá thành công');
    }
}
