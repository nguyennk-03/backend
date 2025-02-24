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
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('discount_id')->nullable()->constrained('discounts')->onDelete('set null');
            $table->enum('status', ['pending', 'completed', 'canceled', 'shipped'])->default('pending');
            $table->decimal('total_price', 10, 2);
            $table->enum('payment_status', ['unpaid', 'paid', 'failed', 'pending'])->default('unpaid');
            $table->timestamps();
        });

    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
}