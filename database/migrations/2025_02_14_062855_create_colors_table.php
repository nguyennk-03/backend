<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateColorsTable extends Migration
{
    public function up()
    {
        Schema::create('colors', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique()->comment('Tên màu');
            $table->string('code', 20)->nullable()->comment('Mã màu (nếu có)');
            $table->string('hex_code', 7)->comment('Mã hex, ví dụ: #FF0000');
            $table->tinyInteger('status')->default(1)->comment('0: Ẩn, 1: Hiển thị');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('colors');
    }
}
