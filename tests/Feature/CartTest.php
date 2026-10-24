<?php

use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

test('guests are redirected to login', function () {
    $this->get(route('cart.index'))->assertRedirect(route('login'));
});

test('authenticated users can add a product to the cart', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('cart.items.store'), ['product_id' => 1, 'quantity' => 2])
        ->assertRedirect();

    $this->actingAs($user)
        ->get(route('cart.index'))
        ->assertInertia(fn (Assert $page) => $page
            ->component('cart/index')
            ->has('items', 1)
            ->where('items.0.product_id', 1)
            ->where('items.0.quantity', 2)
            ->where('items.0.line_total', 698),
        );
});

test('authenticated users can update a cart item quantity', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->post(route('cart.items.store'), ['product_id' => 1, 'quantity' => 1]);
    $this->actingAs($user)->patch(route('cart.items.update', ['productId' => 1]), ['quantity' => 5]);

    $this->actingAs($user)
        ->get(route('cart.index'))
        ->assertInertia(fn (Assert $page) => $page
            ->where('items.0.quantity', 5),
        );
});

test('authenticated users can remove a cart item', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->post(route('cart.items.store'), ['product_id' => 1]);
    $this->actingAs($user)->delete(route('cart.items.destroy', ['productId' => 1]));

    $this->actingAs($user)
        ->get(route('cart.index'))
        ->assertInertia(fn (Assert $page) => $page->has('items', 0));
});
