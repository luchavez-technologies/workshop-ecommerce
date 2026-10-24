<?php

namespace App\Http\Controllers;

use App\Support\Fakes\FakeOrders;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class OrderController extends Controller
{
    /**
     * List the authenticated user's order history.
     */
    public function index(Request $request): Response
    {
        return Inertia::render('orders/index', [
            'orders' => FakeOrders::forUser($request->user()->id),
        ]);
    }

    /**
     * Show a single order belonging to the authenticated user.
     */
    public function show(Request $request, string $order): Response|RedirectResponse
    {
        $found = FakeOrders::find($order);

        if ($found === null || $found['user_id'] !== $request->user()->id) {
            return to_route('orders.index');
        }

        return Inertia::render('orders/show', [
            'order' => $found,
        ]);
    }
}
