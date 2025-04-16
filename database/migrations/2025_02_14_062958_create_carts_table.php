<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartsTable extends Migration
{
    public function up()
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade')->comment('Người dùng');
            $table->foreignId('variant_id')->constrained('product_variants')->onDelete('cascade')->comment('Biến thể sản phẩm');
            $table->integer('quantity')->default(1)->comment('Số lượng sản phẩm');
            $table->decimal('total_price', 10, 2)->comment('Tổng giá (tính từ quantity * giá sản phẩm)');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('carts');
    }
}