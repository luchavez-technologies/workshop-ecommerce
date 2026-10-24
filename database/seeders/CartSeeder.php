<?php

namespace Database\Seeders;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Seeds a sample cart for the "Test User" (test@example.com) once you've
 * built the Cart/CartItem models and migrations (run ProductSeeder first).
 * Uncomment the `CartSeeder::class` call in DatabaseSeeder::run() to try
 * it — a column name mismatch here (e.g. `unit_price` vs `price`) will
 * fail immediately, which tells you your migration doesn't match the
 * README's ERD yet.
 */
class CartSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::query()->where('email', 'test@example.com')->firstOrFail();

        $cart = Cart::query()->updateOrCreate(
            ['user_id' => $user->id, 'status' => 'active'],
            ['user_id' => $user->id, 'status' => 'active'],
        );

        foreach ([
            ['slug' => 'classic-cotton-tee', 'quantity' => 2],
            ['slug' => 'wireless-earbuds', 'quantity' => 1],
        ] as $line) {
            $product = Product::query()->where('slug', $line['slug'])->firstOrFail();

            CartItem::query()->updateOrCreate(
                ['cart_id' => $cart->id, 'product_id' => $product->id],
                [
                    'cart_id' => $cart->id,
                    'product_id' => $product->id,
                    'quantity' => $line['quantity'],
                    'unit_price' => $product->price,
                ],
            );
        }
    }
}
