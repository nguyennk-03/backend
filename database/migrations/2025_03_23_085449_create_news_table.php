<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->string('title')->comment('Tiêu đề bài viết');
            $table->string('slug')->unique()->comment('Slug bài viết');
            $table->text('content')->comment('Nội dung bài viết');
            $table->string('image')->nullable()->comment('Hình ảnh bài viết');
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade')->comment('Danh mục bài viết');
            $table->foreignId('brand_id')->nullable()->constrained('brands')->onDelete('set null')->comment('Thương hiệu liên quan');
            $table->string('author')->comment('Tác giả');
            $table->tinyInteger('status')->default(1)->comment('0: Ẩn, 1: Hiển thị');
            $table->integer('views')->default(0)->comment('Lượt xem');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};
