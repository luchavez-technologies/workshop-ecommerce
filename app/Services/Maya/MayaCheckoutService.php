<?php

namespace App\Services\Maya;

use App\Services\Maya\Data\OrderPayload;
use Illuminate\Http\Client\Factory as HttpFactory;
use RuntimeException;

/**
 * Talks to Maya Checkout's sandbox API.
 *
 * @see https://developers.maya.ph/reference/createv1checkout
 */
class MayaCheckoutService
{
    public function __construct(private readonly HttpFactory $http) {}

    /**
     * Create a Maya Checkout session for the given order and return the
     * details to persist on the order's payment record.
     *
     * @return array{checkout_id: string, redirect_url: string, request_reference_number: string, raw_response: array<string, mixed>}
     */
    public function createCheckout(OrderPayload $order): array
    {
        $payload = [
            ...$this->buildPayload($order),
            'requestReferenceNumber' => $order->orderNumber,
            'redirectUrl' => [
                'success' => $this->redirectUrl('success', $order->orderNumber),
                'failure' => $this->redirectUrl('failure', $order->orderNumber),
                'cancel' => $this->redirectUrl('cancel', $order->orderNumber),
            ],
        ];

        if ($order->buyerName !== null || $order->buyerEmail !== null) {
            $payload['buyer'] = array_filter([
                'firstName' => $order->buyerName,
                'contact' => array_filter(['email' => $order->buyerEmail]),
            ]);
        }

        $response = $this->http
            ->baseUrl(config('maya.base_url'))
            ->withBasicAuth(config('maya.public_key'), '')
            ->acceptJson()
            ->post('/checkout/v1/checkouts', $payload)
            ->throw();

        return [
            'checkout_id' => $response->json('checkoutId'),
            'redirect_url' => $response->json('redirectUrl'),
            'request_reference_number' => $order->orderNumber,
            'raw_response' => $response->json(),
        ];
    }

    /**
     * Translate a Maya webhook payload (same shape as the Get Checkout
     * response) into the status/fields the payment record should store.
     *
     * @param  array<string, mixed>  $payload
     * @return array{checkout_id: ?string, status: string, paid_at: ?string}
     */
    public function handleWebhook(array $payload): array
    {
        return [
            'checkout_id' => $payload['checkoutId'] ?? null,
            'status' => $this->mapPaymentStatus($payload['status'] ?? null),
            'paid_at' => ($payload['status'] ?? null) === 'PAYMENT_SUCCESS'
                ? now()->toIso8601String()
                : null,
        ];
    }

    protected function redirectUrl(string $key, string $orderNumber): string
    {
        return url(config("maya.redirect.{$key}")).'?order='.$orderNumber;
    }

    protected function mapPaymentStatus(?string $mayaStatus): string
    {
        return match ($mayaStatus) {
            'PAYMENT_SUCCESS' => 'success',
            'PAYMENT_FAILED' => 'failed',
            'PAYMENT_EXPIRED' => 'expired',
            default => 'pending',
        };
    }

    /**
     * Build the `totalAmount`/`items` portion of the Create Checkout
     * request body from the order's line items.
     *
     * WORKSHOP EXERCISE: implement this method. Using $order->items (each
     * shaped like ['name' => ..., 'quantity' => ..., 'unit_price' => ...,
     * 'line_total' => ...]) and $order->subtotal / discount / shippingFee
     * / tax / total / currency, return:
     *
     *   [
     *       'totalAmount' => [
     *           'value' => $order->total,
     *           'currency' => $order->currency,
     *           'details' => [
     *               'subtotal' => $order->subtotal,
     *               'discount' => $order->discount,
     *               'serviceCharge' => 0,
     *               'shippingFee' => $order->shippingFee,
     *               'tax' => $order->tax,
     *           ],
     *       ],
     *       'items' => [
     *           // one entry per $order->items, each with 'name', 'quantity',
     *           // 'amount' => ['value' => unit_price], and
     *           // 'totalAmount' => ['value' => line_total]
     *       ],
     *   ]
     *
     * @see https://developers.maya.ph/reference/createv1checkout
     *
     * @return array{totalAmount: array<string, mixed>, items: array<int, array<string, mixed>>}
     */
    public function buildPayload(OrderPayload $order): array
    {
        throw new RuntimeException(
            'TODO(workshop): implement MayaCheckoutService::buildPayload() — see the exercise notes above this method.'
        );
    }
}
