<?php

namespace App\Support\Fakes;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

/**
 * Stand-in for the `orders`/`order_items`/`payments` tables described in
 * the README's ERD. Orders are kept in the session rather than a database
 * table, so this only demonstrates the shape of the data — it does not
 * persist across sessions/restarts, and the "admin" view only ever sees
 * orders placed in the current browser session. That limitation goes away
 * the moment these are backed by real Eloquent models.
 *
 * TODO(workshop): delete this class once `orders`/`order_items`/`payments`
 * migrations and models exist, and replace every call site with real
 * Eloquent queries (scoped to the authenticated user where relevant).
 */
class FakeOrders
{
    protected const string SESSION_KEY = 'fake_orders';

    /**
     * @return array<string, array<string, mixed>> keyed by order_number
     */
    protected static function raw(): array
    {
        return Session::get(self::SESSION_KEY, []);
    }

    protected static function save(array $orders): void
    {
        Session::put(self::SESSION_KEY, $orders);
    }

    /**
     * @return Collection<int, array<string, mixed>>
     */
    public static function forUser(int $userId): Collection
    {
        return collect(self::raw())
            ->filter(fn (array $order) => $order['user_id'] === $userId)
            ->sortByDesc('placed_at')
            ->values();
    }

    /**
     * @return Collection<int, array<string, mixed>>
     */
    public static function all(): Collection
    {
        return collect(self::raw())->sortByDesc('placed_at')->values();
    }

    public static function find(string $orderNumber): ?array
    {
        return self::raw()[$orderNumber] ?? null;
    }

    public static function findByCheckoutId(string $checkoutId): ?array
    {
        return self::all()->first(
            fn (array $order) => ($order['payment']['checkout_id'] ?? null) === $checkoutId
        );
    }

    /**
     * Create a pending order (+ pending payment) from the current cart.
     *
     * @return array<string, mixed>
     */
    public static function createFromCart(int $userId): array
    {
        $items = FakeCart::items();
        $totals = FakeCart::totals();

        $order = [
            'id' => count(self::raw()) + 1,
            'order_number' => 'ORD-'.now()->format('Ymd').'-'.Str::upper(Str::random(6)),
            'user_id' => $userId,
            'status' => 'pending',
            'items' => $items->map(fn (array $item) => [
                'product_id' => $item['product_id'],
                'product_name' => $item['product']['name'],
                'unit_price' => $item['unit_price'],
                'quantity' => $item['quantity'],
                'line_total' => $item['line_total'],
            ])->all(),
            'subtotal' => $totals['subtotal'],
            'discount' => $totals['discount'],
            'shipping_fee' => $totals['shipping_fee'],
            'tax' => $totals['tax'],
            'total' => $totals['total'],
            'currency' => 'PHP',
            'placed_at' => now()->toIso8601String(),
            'paid_at' => null,
            'cancelled_at' => null,
            'payment' => [
                'provider' => 'maya',
                'checkout_id' => null,
                'request_reference_number' => null,
                'redirect_url' => null,
                'status' => 'pending',
                'currency' => 'PHP',
                'amount' => $totals['total'],
                'paid_at' => null,
            ],
        ];

        $orders = self::raw();
        $orders[$order['order_number']] = $order;
        self::save($orders);

        return $order;
    }

    /**
     * @param  array<string, mixed>  $paymentAttributes
     */
    public static function updatePayment(string $orderNumber, array $paymentAttributes): ?array
    {
        $orders = self::raw();

        if (! isset($orders[$orderNumber])) {
            return null;
        }

        $orders[$orderNumber]['payment'] = [
            ...$orders[$orderNumber]['payment'],
            ...$paymentAttributes,
        ];

        self::save($orders);

        return $orders[$orderNumber];
    }

    public static function updateStatus(string $orderNumber, string $status): ?array
    {
        $orders = self::raw();

        if (! isset($orders[$orderNumber])) {
            return null;
        }

        $orders[$orderNumber]['status'] = $status;

        if ($status === 'paid') {
            $orders[$orderNumber]['paid_at'] = now()->toIso8601String();
        }

        if ($status === 'cancelled') {
            $orders[$orderNumber]['cancelled_at'] = now()->toIso8601String();
        }

        self::save($orders);

        return $orders[$orderNumber];
    }
}
