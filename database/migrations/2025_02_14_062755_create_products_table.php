<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Tên sản phẩm');
            $table->text('description')->nullable()->comment('Mô tả sản phẩm');
            $table->tinyInteger('sale')->default(0)->comment('0: Không giảm giá, 1: Đang giảm giá');
            $table->tinyInteger('hot')->default(0)->comment('0: Thường, 1: Mới, 2: Nổi bật, 3: Bán chạy');
            $table->tinyInteger('status')->default(1)->comment('0: Ẩn, 1: Hiển thị');
            $table->integer('stock_quantity')->default(0)->comment('Tổng tồn kho');
            $table->integer('sold')->default(0)->comment('Số lượng đã bán');
            $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('set null')->comment('Danh mục sản phẩm');
            $table->foreignId('brand_id')->nullable()->constrained('brands')->onDelete('set null')->comment('Thương hiệu sản phẩm');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
}