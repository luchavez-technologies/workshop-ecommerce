<?php

namespace App\Http\Controllers;

use App\Services\Maya\MayaCheckoutService;
use App\Support\Fakes\FakeOrders;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MayaWebhookController extends Controller
{
    /**
     * Receive a Maya Checkout webhook (CHECKOUT_SUCCESS/FAILURE/DROPOUT).
     *
     * This route is registered without CSRF protection or auth — Maya
     * posts here directly, outside of any browser session.
     *
     * TODO: verify Maya's webhook signature before trusting the payload,
     * once the signing scheme for Checkout webhooks (as opposed to the
     * Biller API's `paymaya-signature` header) is confirmed with Maya.
     */
    public function handle(Request $request, MayaCheckoutService $maya): JsonResponse
    {
        $payload = $request->all();

        $result = $maya->handleWebhook($payload);

        if ($result['checkout_id'] !== null) {
            $order = FakeOrders::findByCheckoutId($result['checkout_id']);

            if ($order !== null) {
                FakeOrders::updatePayment($order['order_number'], [
                    'status' => $result['status'],
                    'paid_at' => $result['paid_at'],
                    'webhook_payload' => $payload,
                ]);

                FakeOrders::updateStatus(
                    $order['order_number'],
                    $result['status'] === 'success' ? 'paid' : $result['status'],
                );
            }
        }

        return response()->json(['received' => true]);
    }
}
