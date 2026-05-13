<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    private const STATUSES = ['new', 'processing', 'shipped', 'delivered', 'cancelled'];

    public function run(): void
    {
        $customerIds = Customer::pluck('id');
        $products = Product::all(['id', 'price']);

        foreach ($customerIds as $customerId) {
            $orderCount = fake()->numberBetween(1, 4);

            for ($i = 0; $i < $orderCount; $i++) {
                $order = Order::create([
                    'customer_id' => $customerId,
                    'status' => fake()->randomElement(self::STATUSES),
                    'notes' => fake()->boolean(30) ? fake()->sentence() : null,
                ]);

                $itemCount = fake()->numberBetween(1, 5);
                $pickedProducts = $products->random(min($itemCount, $products->count()));

                foreach ($pickedProducts as $product) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'qty' => fake()->numberBetween(1, 10),
                        'unit_price' => $product->price,
                    ]);
                }
            }
        }
    }
}
