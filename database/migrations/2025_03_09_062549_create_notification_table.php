<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade')->comment('Người dùng');
            $table->string('title')->comment('Tiêu đề thông báo');
            $table->text('message')->comment('Nội dung thông báo');
            $table->string('link')->nullable()->comment('Liên kết liên quan');
            $table->enum('status', ['unread', 'read'])->default('unread')->comment('Trạng thái thông báo');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('notifications');
    }
};
