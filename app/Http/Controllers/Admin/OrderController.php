<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\Fakes\FakeOrders;
use Inertia\Inertia;
use Inertia\Response;

class OrderController extends Controller
{
    /**
     * List every order placed in the current session.
     *
     * NOTE: because orders aren't persisted to a real table yet, this only
     * shows orders placed during the current browser session — not every
     * order ever placed. That limitation disappears once `orders` is a
     * real Eloquent model.
     */
    public function index(): Response
    {
        return Inertia::render('admin/orders/index', [
            'orders' => FakeOrders::all(),
        ]);
    }
}
