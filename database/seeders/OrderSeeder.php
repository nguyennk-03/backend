<?php

namespace Database\Seeders;

use App\Enums\OrderStatusEnum;
use App\Enums\PaymentStatusEnum;
use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\User;
use App\Models\Discount;
use App\Models\Payment;
use App\Models\Product;
use App\Models\OrderItem;
use Faker\Factory as Faker;

class OrderSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('vi_VN');

        $userIds = User::pluck('id')->toArray();
        $discounts = Discount::all()->keyBy('id');
        $paymentIds = Payment::pluck('id')->toArray();
        $products = Product::all();

        // Tính từ tháng hiện tại trở về 12 tháng trước
        $start = new \DateTime();
        $months = [];
        for ($i = 0; $i < 12; $i++) {
            $months[] = $start->format('Y-m');
            $start->modify('-1 month');
        }

        foreach ($userIds as $userId) {
            foreach ($months as $month) {
                $orderCount = rand(2, 4); // mỗi user mỗi tháng có 2 hoặc 3 đơn
                for ($i = 0; $i < $orderCount; $i++) {
                    $endOfMonth = (new \DateTime("{$month}-01"))->modify('last day of this month');
                    $createdAt = $faker->dateTimeBetween("{$month}-01", $endOfMonth->format('Y-m-d'));

                    $paymentId = $paymentIds ? $faker->randomElement($paymentIds) : null;
                    $statusEnum = $faker->randomElement(OrderStatusEnum::cases());

                    $paymentStatusEnum = match ($statusEnum) {
                        OrderStatusEnum::PENDING,
                        OrderStatusEnum::AWAITING_CONFIRMATION => PaymentStatusEnum::PENDING,
                        OrderStatusEnum::PROCESSING,
                        OrderStatusEnum::PACKING,
                        OrderStatusEnum::SHIPPED,
                        OrderStatusEnum::DELIVERED,
                        OrderStatusEnum::RETURN_REQUESTED,
                        OrderStatusEnum::RETURN_PROCESSING => PaymentStatusEnum::PAID,
                        OrderStatusEnum::RETURNED => PaymentStatusEnum::REFUNDED,
                        OrderStatusEnum::CANCELED => PaymentStatusEnum::CANCELED,
                        default => PaymentStatusEnum::PENDING,
                    };

                    $status = $statusEnum->value;
                    $paymentStatus = $paymentStatusEnum->value;

                    $order = Order::create([
                        'code' => 'STEPVIET' . strtoupper(uniqid()),
                        'user_id' => $userId,
                        'discount_id' => null,
                        'payment_id' => $paymentId,
                        'payment_status' => $paymentStatus,
                        'status' => $status,
                        'total_price' => 0,
                        'total_after_discount' => null,
                        'recipient_name' => $faker->name(),
                        'recipient_phone' => $faker->phoneNumber(),
                        'shipping_address' => $faker->address(),
                        'note' => $faker->optional(0.5)->text(100),
                        'tracking_code' => $faker->optional(0.3)->regexify('[A-Z]{2}[0-9]{8}VN'),
                        'created_at' => $createdAt,
                        'updated_at' => $createdAt,
                    ]);

                    $selectedProducts = $products->random(rand(1, min(2, $products->count())));
                    $realTotal = 0;

                    foreach ($selectedProducts as $product) {
                        $price = $product->price ?? 0;
                        $quantity = rand(1, 3);
                        $realTotal += $price * $quantity;

                        OrderItem::create([
                            'order_id' => $order->id,
                            'product_id' => $product->id,
                            'quantity' => $quantity,
                            'price' => $price,
                        ]);
                    }

                    $discountId = null;
                    $discountAmount = 0;
                    if (!empty($discounts) && rand(0, 1)) {
                        $discount = $faker->randomElement($discounts->values()->all());
                        $discountId = $discount->id;

                        if ($discount->discount_type === 'percentage') {
                            $discountValue = min(max($discount->value, 0), 100);
                            $discountAmount = $realTotal * ($discountValue / 100);
                        } else {
                            $discountAmount = max($discount->value, 0);
                        }
                    }

                    $finalPrice = max(0, $realTotal - $discountAmount);

                    $order->update([
                        'discount_id' => $discountId,
                        'total_price' => $realTotal/100,
                        'total_after_discount' => $discountId ? $finalPrice : null,
                    ]);
                }
            }
        }
    }
}
