<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::query();

        if ($request->filled('search')) {
            $query->where('id', 'like', '%' . $request->search . '%')
                ->orWhereHas('user', function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->search . '%');
                });
        }

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
        $order = Order::findOrFail($id);
        return view('admin.orders.view', compact('order'));
    }

    public function edit($id)
    {
        $order = Order::findOrFail($id);
        return view('admin.orders.edit', compact('order'));
    }

    public function update(Request $request, $id)
    {
        Order::findOrFail($id)->update($request->all());
        return redirect()->route('orders')->with('success', 'Cập nhật đơn hàng thành công');
    }

    public function destroy($id)
    {
        Order::destroy($id);
        return redirect()->route('orders')->with('success', 'Xóa đơn hàng thành công');
    }
}
