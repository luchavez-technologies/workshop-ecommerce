<?php

namespace App\Http\Controllers;

use App\Support\Fakes\FakeCart;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CartController extends Controller
{
    /**
     * Show the current user's cart.
     */
    public function index(): Response
    {
        return Inertia::render('cart/index', [
            'items' => FakeCart::items(),
            'totals' => FakeCart::totals(),
        ]);
    }

    /**
     * Add a product to the cart.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'integer'],
            'quantity' => ['nullable', 'integer', 'min:1'],
        ]);

        FakeCart::add($validated['product_id'], $validated['quantity'] ?? 1);

        return back();
    }

    /**
     * Update a cart line's quantity.
     */
    public function update(Request $request, int $productId): RedirectResponse
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:0'],
        ]);

        FakeCart::updateQuantity($productId, $validated['quantity']);

        return back();
    }

    /**
     * Remove a product from the cart.
     */
    public function destroy(int $productId): RedirectResponse
    {
        FakeCart::remove($productId);

        return back();
    }
}
