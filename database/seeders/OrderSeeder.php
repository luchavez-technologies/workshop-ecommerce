<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Seeds a sample paid order (with order items + a Maya payment record) for
 * the "Test User" (test@example.com) once you've built the
 * Order/OrderItem/Payment models and migrations (run ProductSeeder first).
 * Uncomment the `OrderSeeder::class` call in DatabaseSeeder::run() to try
 * it — a column name mismatch here will fail immediately, which tells you
 * your migration doesn't match the README's ERD yet.
 */
class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::query()->where('email', 'test@example.com')->firstOrFail();

        $lines = collect([
            ['slug' => 'denim-jacket', 'quantity' => 1],
            ['slug' => 'ceramic-mug-set', 'quantity' => 2],
        ])->map(function (array $line) {
            $product = Product::query()->where('slug', $line['slug'])->firstOrFail();

            return [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'unit_price' => $product->price,
                'quantity' => $line['quantity'],
                'line_total' => round($product->price * $line['quantity'], 2),
            ];
        });

        $subtotal = round($lines->sum('line_total'), 2);
        $shippingFee = 100.00;
        $tax = round($subtotal * 0.12, 2);
        $total = round($subtotal + $shippingFee + $tax, 2);

        $order = Order::query()->updateOrCreate(
            ['order_number' => 'ORD-SEEDED-0001'],
            [
                'user_id' => $user->id,
                'order_number' => 'ORD-SEEDED-0001',
                'status' => 'paid',
                'subtotal' => $subtotal,
                'discount' => 0,
                'shipping_fee' => $shippingFee,
                'tax' => $tax,
                'total' => $total,
                'currency' => 'PHP',
                'placed_at' => now()->subHour(),
                'paid_at' => now(),
                'cancelled_at' => null,
            ],
        );

        foreach ($lines as $line) {
            OrderItem::query()->updateOrCreate(
                ['order_id' => $order->id, 'product_id' => $line['product_id']],
                [...$line, 'order_id' => $order->id],
            );
        }

        Payment::query()->updateOrCreate(
            ['order_id' => $order->id],
            [
                'order_id' => $order->id,
                'provider' => 'maya',
                'checkout_id' => 'seeded-checkout-'.Str::uuid(),
                'request_reference_number' => $order->order_number,
                'redirect_url' => null,
                'status' => 'success',
                'currency' => 'PHP',
                'amount' => $total,
                'raw_response' => null,
                'webhook_payload' => null,
                'paid_at' => now(),
            ],
        );
    }
}
