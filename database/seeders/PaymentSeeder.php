<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Payment;
use App\Models\Order;
use App\Models\User;
use Faker\Factory as Faker;
use Faker\Guesser\Name;

class PaymentSeeder extends Seeder
{
    public function run()
    {
        $paymentMethods = ['Momo', 'VNPay', 'PayPal', 'COD'];

        foreach ($paymentMethods as $method) {
            Payment::create([
                'name' => $method,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
