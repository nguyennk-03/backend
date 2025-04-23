<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderItemsTable extends Migration
{
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade')->comment('Đơn hàng');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade')->comment('Sản phẩm');
            $table->integer('quantity')->default(1)->comment('Số lượng sản phẩm');
            $table->decimal('price', 10, 2)->comment('Giá sản phẩm tại thời điểm đặt hàng');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('order_items');
    }
}