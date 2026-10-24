<?php

namespace App\Http\Controllers;

use App\Services\Maya\Data\OrderPayload;
use App\Services\Maya\MayaCheckoutService;
use App\Support\Fakes\FakeCart;
use App\Support\Fakes\FakeOrders;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CheckoutController extends Controller
{
    /**
     * Show an order summary before paying.
     */
    public function show(): Response|RedirectResponse
    {
        if (FakeCart::items()->isEmpty()) {
            return to_route('cart.index');
        }

        return Inertia::render('checkout/show', [
            'items' => FakeCart::items(),
            'totals' => FakeCart::totals(),
        ]);
    }

    /**
     * Create an order from the cart and send the buyer to Maya Checkout.
     */
    public function store(Request $request, MayaCheckoutService $maya): RedirectResponse
    {
        if (FakeCart::items()->isEmpty()) {
            return to_route('cart.index');
        }

        $order = FakeOrders::createFromCart($request->user()->id);

        $payload = new OrderPayload(
            orderNumber: $order['order_number'],
            currency: $order['currency'],
            subtotal: $order['subtotal'],
            discount: $order['discount'],
            shippingFee: $order['shipping_fee'],
            tax: $order['tax'],
            total: $order['total'],
            items: $order['items'],
            buyerName: $request->user()->name,
            buyerEmail: $request->user()->email,
        );

        // NOTE: this throws until MayaCheckoutService::buildPayload() is
        // implemented (the workshop exercise) — that's expected.
        $checkout = $maya->createCheckout($payload);

        FakeOrders::updatePayment($order['order_number'], [
            'checkout_id' => $checkout['checkout_id'],
            'request_reference_number' => $checkout['request_reference_number'],
            'redirect_url' => $checkout['redirect_url'],
            'raw_response' => $checkout['raw_response'],
        ]);

        FakeCart::clear();

        return Inertia::location($checkout['redirect_url']);
    }

    /**
     * Maya redirected the buyer back here after a successful payment.
     */
    public function success(Request $request): Response
    {
        $orderNumber = (string) $request->query('order');

        return Inertia::render('checkout/success', [
            'order' => FakeOrders::find($orderNumber),
        ]);
    }

    /**
     * Maya redirected the buyer back here after a failed/cancelled payment.
     */
    public function cancel(Request $request): Response
    {
        $orderNumber = (string) $request->query('order');

        return Inertia::render('checkout/cancel', [
            'order' => FakeOrders::find($orderNumber),
        ]);
    }
}
