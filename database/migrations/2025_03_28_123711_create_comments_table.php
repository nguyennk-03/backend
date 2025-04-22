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
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade')->comment('Người dùng');
            $table->foreignId('product_id')->nullable()->constrained('products')->onDelete('cascade')->comment('Sản phẩm');
            $table->text('message')->comment('Nội dung bình luận');
            $table->boolean('is_staff')->default(false)->comment('Bình luận từ nhân viên?');
            $table->foreignId('parent_id')->nullable()->constrained('comments')->onDelete('cascade')->comment('Bình luận cha');
            $table->tinyInteger('is_hidden')->default(0)->comment('0: Ẩn, 1: Hiển thị');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
