<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiscountsTable extends Migration
{
    public function up()
    {
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Tên mã giảm giá');
            $table->string('code')->unique()->comment('Mã giảm giá');
            $table->tinyInteger('discount_type')->default(0)->comment('0: percentage, 1: fixed');
            $table->decimal('value', 10, 2)->comment('Giá trị giảm');
            $table->decimal('min_order_amount', 10, 2)->default(0)->comment('Giá trị đơn hàng tối thiểu');
            $table->dateTime('start_date')->nullable()->comment('Ngày bắt đầu');
            $table->dateTime('end_date')->nullable()->comment('Ngày kết thúc');
            $table->boolean('is_active')->default(true)->comment('Trạng thái mã giảm giá');
            $table->integer('usage_limit')->nullable()->comment('Giới hạn số lần sử dụng');
            $table->integer('used_count')->default(0)->comment('Số lần đã sử dụng');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('discounts');
    }
}
