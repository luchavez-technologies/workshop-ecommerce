<?php

namespace App\Services\Maya\Data;

use App\Services\Maya\MayaCheckoutService;

/**
 * A plain, framework-agnostic view of "the order being paid for", passed
 * into {@see MayaCheckoutService}. Using a DTO instead
 * of typing the service against an Eloquent `Order` means the service's
 * method signatures won't need to change once the workshop's fake-array
 * orders are replaced with real `Order`/`OrderItem` models — only the code
 * that *builds* this DTO changes.
 */
final readonly class OrderPayload
{
    /**
     * @param  array<int, array{name: string, quantity: int, unit_price: float, line_total: float}>  $items
     */
    public function __construct(
        public string $orderNumber,
        public string $currency,
        public float $subtotal,
        public float $discount,
        public float $shippingFee,
        public float $tax,
        public float $total,
        public array $items,
        public ?string $buyerName = null,
        public ?string $buyerEmail = null,
    ) {}
}
