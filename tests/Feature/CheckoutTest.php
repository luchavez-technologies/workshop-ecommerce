<?php

use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

test('guests are redirected to login', function () {
    $this->get(route('checkout.show'))->assertRedirect(route('login'));
});

test('checkout redirects to the cart when empty', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('checkout.show'))
        ->assertRedirect(route('cart.index'));
});

test('checkout shows an order summary for a non-empty cart', function () {
    $user = User::factory()->create();
    $this->actingAs($user)->post(route('cart.items.store'), ['product_id' => 1, 'quantity' => 1]);

    $this->actingAs($user)
        ->get(route('checkout.show'))
        ->assertInertia(fn (Assert $page) => $page
            ->component('checkout/show')
            ->has('items', 1),
        );
});

/**
 * MayaCheckoutService::buildPayload() is the workshop exercise and throws
 * until it's implemented — this documents that expected, current behavior.
 * Once a student implements it, this test should be replaced with one that
 * asserts the checkout session actually gets created.
 */
test('checkout fails until buildPayload() is implemented', function () {
    $user = User::factory()->create();
    $this->actingAs($user)->post(route('cart.items.store'), ['product_id' => 1, 'quantity' => 1]);

    $this->actingAs($user)
        ->post(route('checkout.store'))
        ->assertStatus(500);
});
