<?php

namespace App\Support\Fakes;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;

/**
 * Stand-in for the `carts`/`cart_items` tables described in the README's
 * ERD. Backed by the session (one cart per logged-in session) instead of
 * a database row — every item is shaped like a future `cart_items` record,
 * including a price snapshot taken at add-to-cart time.
 *
 * TODO(workshop): delete this class once `carts`/`cart_items` migrations
 * and models exist, and replace every call site with a real `Cart` model
 * scoped to the authenticated user.
 */
class FakeCart
{
    protected const string SESSION_KEY = 'fake_cart';

    /**
     * @return array<int, array{quantity: int, unit_price: float}> keyed by product_id
     */
    protected static function raw(): array
    {
        return Session::get(self::SESSION_KEY, []);
    }

    protected static function save(array $cart): void
    {
        Session::put(self::SESSION_KEY, $cart);
    }

    /**
     * @return Collection<int, array<string, mixed>>
     */
    public static function items(): Collection
    {
        return collect(self::raw())
            ->map(function (array $line, int $productId) {
                $product = FakeCatalog::find($productId);

                if ($product === null) {
                    return null;
                }

                return [
                    'product_id' => $productId,
                    'product' => $product,
                    'quantity' => $line['quantity'],
                    'unit_price' => $line['unit_price'],
                    'line_total' => round($line['unit_price'] * $line['quantity'], 2),
                ];
            })
            ->filter()
            ->values();
    }

    public static function add(int $productId, int $quantity = 1): void
    {
        $product = FakeCatalog::find($productId);

        if ($product === null) {
            return;
        }

        $cart = self::raw();
        $existingQuantity = $cart[$productId]['quantity'] ?? 0;

        $cart[$productId] = [
            'quantity' => $existingQuantity + $quantity,
            'unit_price' => $product['price'],
        ];

        self::save($cart);
    }

    public static function updateQuantity(int $productId, int $quantity): void
    {
        $cart = self::raw();

        if (! isset($cart[$productId])) {
            return;
        }

        if ($quantity <= 0) {
            unset($cart[$productId]);
        } else {
            $cart[$productId]['quantity'] = $quantity;
        }

        self::save($cart);
    }

    public static function remove(int $productId): void
    {
        $cart = self::raw();
        unset($cart[$productId]);
        self::save($cart);
    }

    public static function clear(): void
    {
        self::save([]);
    }

    /**
     * @return array{subtotal: float, shipping_fee: float, tax: float, discount: float, total: float}
     */
    public static function totals(): array
    {
        $subtotal = round(self::items()->sum('line_total'), 2);
        $shippingFee = $subtotal > 0 ? 100.00 : 0.0;
        $tax = round($subtotal * 0.12, 2);

        return [
            'subtotal' => $subtotal,
            'shipping_fee' => $shippingFee,
            'tax' => $tax,
            'discount' => 0.0,
            'total' => round($subtotal + $shippingFee + $tax, 2),
        ];
    }
}
