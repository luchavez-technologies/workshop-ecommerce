<?php

use Inertia\Testing\AssertableInertia as Assert;

test('shop index lists active products', function () {
    $this->get(route('shop.index'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('shop/index')
            ->has('products', 10)
            ->has('categories', 4),
        );
});

test('shop show displays a single product', function () {
    $this->get(route('shop.show', 'classic-cotton-tee'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('shop/show')
            ->where('product.slug', 'classic-cotton-tee'),
        );
});

test('shop show redirects back to the shop for an unknown product', function () {
    $this->get(route('shop.show', 'does-not-exist'))
        ->assertRedirect(route('shop.index'));
});
