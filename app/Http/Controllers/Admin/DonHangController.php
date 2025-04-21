<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Validation\Rules\Enum;
use App\Enums\OrderStatusEnum;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DonHangController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::query();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->with('user')->get();

        $users = User::all();
        $products = Product::all();

        return view('admin.orders.index', compact('orders', 'users', 'products'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'total_price' => 'required|numeric|min:0',
            'status' => 'required|string|in:pending,processing,completed,canceled',
        ]);

        $order = Order::create($request->all());

        return redirect()->route('orders.index')->with('success', 'Đơn hàng đã được tạo thành công.');
    }

    public function show($id)
    {
        $order = Order::with('user', 'orderItems.product')->findOrFail($id);

        return view('admin.orders.show', compact('order'));
    }

    public function edit($id)
    {
        $order = Order::findOrFail($id);
        return view('admin.orders.edit', compact('order'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => ['required', Rule::in(array_column(OrderStatusEnum::cases(), 'value'))],
        ]);

        $order = Order::findOrFail($id);
        $order->status = OrderStatusEnum::from($request->input('status')); // Chuyển string thành Enum
        $order->save();

        return back()->with('success', 'Cập nhật trạng thái thành công!');
    }

    public function destroy($id)
    {
        Order::destroy($id);
        return redirect()->route('orders')->with('success', 'Xóa đơn hàng thành công');
    }
}
