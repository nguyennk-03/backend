<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSizesTable extends Migration
{
    public function up()
    {
        Schema::create('sizes', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique()->comment('Tên kích thước');
            $table->decimal('cm', 6, 1)->nullable()->comment('Kích thước tính bằng cm');
            $table->tinyInteger('status')->default(1)->comment('0: Ẩn, 1: Hiển thị');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sizes');
    }
}
