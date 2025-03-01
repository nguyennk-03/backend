<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('discount_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('payment_id')->nullable()->constrained('payments')->onDelete('set null');
            $table->enum('status', ['pending', 'completed', 'canceled'])->default('pending');
            $table->decimal('total_price', 10, 2);
            $table->enum('payment_method', ['momo', 'vnpay', 'paypal', 'cod'])->default('cod');
            $table->timestamps();
        });

    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
}