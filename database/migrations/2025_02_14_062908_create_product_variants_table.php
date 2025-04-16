<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductVariantsTable extends Migration
{
    public function up()
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade')->comment('Sản phẩm');
            $table->foreignId('size_id')->nullable()->constrained('sizes')->onDelete('set null')->comment('Kích thước');
            $table->foreignId('color_id')->nullable()->constrained('colors')->onDelete('set null')->comment('Màu sắc');
            $table->decimal('price', 10, 2)->comment('Giá gốc');
            $table->unsignedTinyInteger('discount_percent')->default(0)->comment('Phần trăm giảm giá (0-100%)');
            $table->decimal('discounted_price', 10, 2)->nullable()->comment('Giá sau giảm');
            $table->integer('stock_quantity')->default(0)->comment('Tổng tồn kho');
            $table->integer('sold')->default(0)->comment('Số lượng đã bán');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_variants');
    }
}
