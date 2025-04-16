<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique()->comment('Mã đơn hàng');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade')->comment('Người dùng');
            $table->foreignId('discount_id')->nullable()->constrained('discounts')->onDelete('set null')->comment('Mã giảm giá');
            $table->foreignId('payment_id')->nullable()->default(1)->constrained('payments')->onDelete('set null')->comment('Phương thức thanh toán');
            $table->tinyInteger('status')->default(0)->comment('0: Chờ xử lý, 1: Đang xử lý, 2: Đã giao, 3: Hoàn thành, 4: Hủy, 5: Trả hàng');
            $table->tinyInteger('payment_status')->default(0)->comment('0: Chờ thanh toán, 1: Đã thanh toán, 2: Thất bại, 3: Hoàn tiền');
            $table->decimal('total_price', 10, 2)->default(0)->comment('Tổng giá đơn hàng');
            $table->decimal('total_after_discount', 10, 2)->nullable()->comment('Tổng giá sau giảm giá');
            $table->string('tracking_code')->nullable()->comment('Mã vận đơn');
            $table->string('recipient_name')->comment('Tên người nhận');
            $table->string('recipient_phone')->comment('Số điện thoại người nhận');
            $table->text('shipping_address')->comment('Địa chỉ giao hàng');
            $table->string('note')->nullable()->comment('Ghi chú');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
