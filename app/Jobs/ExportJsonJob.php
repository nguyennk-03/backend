<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log; // Import the Log facade
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Color;
use App\Models\Size;
use App\Models\Image;
use App\Models\Order;
use App\Models\Cart;
use App\Models\Payment;
use App\Models\Review;
use App\Models\User;
use App\Models\ProductVariant;
use App\Models\OrderItem;
use App\Models\Discount;

class ExportJsonJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Danh sách các bảng cần xuất JSON
        $tables = [
            'brands' => Brand::all(),
            'categories' => Category::all(),
            'products' => Product::all(),
            'colors' => Color::all(),
            'sizes' => Size::all(),
            'images' => Image::all(),
            'orders' => Order::all(),
            'carts' => Cart::all(),
            'payments' => Payment::all(),
            'users' => User::all(),
            'variants' => ProductVariant::all(),
            'orderitems' => OrderItem::all(),
            'discounts' => Discount::all(),
        ];

        // Lưu từng bảng vào file JSON
        foreach ($tables as $name => $data) {
            Storage::put("public/{$name}.json", json_encode($data, JSON_PRETTY_PRINT));
        }

        // Ghi log kiểm tra
        Log::info('ExportJsonJob: Đã xuất thành công các file JSON.');
    }
}