<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImagesTable extends Migration
{
    public function up()
    {
        Schema::create('images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('variant_id')->constrained('product_variants')->onDelete('cascade')->comment('ID biến thể sản phẩm');
            $table->string('image')->unique()->comment('Đường dẫn ảnh (local hoặc URL)');
            $table->boolean('is_main')->default(false)->comment('Là ảnh chính?');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('images');
    }
}
