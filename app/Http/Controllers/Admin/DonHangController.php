<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Validation\Rules\Enum;
use App\Enums\OrderStatusEnum;
use App\Enums\PaymentStatusEnum;
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
        // Build query for orders with eager-loaded relations
        $query = Order::with(['user', 'items'])->latest();

        // Apply search filter
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // Apply status filter
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        // Get all results without pagination
        $orders = $query->get();

        // Fetch users and products for the "Add Order" modal
        $users = User::select('id', 'name', 'email')->get();
        $products = Product::select('id', 'name', 'price')->get();

        return view('admin.orders.index', compact('orders', 'users', 'products'));
    }


    public function show($id)
    {
        $order = Order::with('user', 'items.product')->findOrFail($id);

        return view('admin.orders.view', compact('order'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => ['required', Rule::in(array_column(OrderStatusEnum::cases(), 'value'))],
        ]);

        $order = Order::findOrFail($id);
        $order->status = OrderStatusEnum::from($request->input('status'));
        $order->save();

        return back()->with('success', 'Cập nhật trạng thái thành công!');
    }
}
