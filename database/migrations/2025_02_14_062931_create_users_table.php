<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Tên người dùng');
            $table->string('email')->unique()->comment('Email người dùng');
            $table->timestamp('email_verified_at')->nullable()->comment('Thời gian xác thực email');
            $table->string('password')->comment('Mật khẩu');
            $table->tinyInteger('is_locked')->default(0)->comment('0: Không khóa, 1: Khóa');
            $table->tinyInteger('status')->default(0)->comment('0: Không hoạt động, 1: Hoạt động');
            $table->string('phone')->nullable()->comment('Số điện thoại');
            $table->string('avatar')->nullable()->comment('Ảnh đại diện');
            $table->text('address')->nullable()->comment('Địa chỉ');
            $table->string('role')->default('user')->comment('Vai trò: user, admin');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}