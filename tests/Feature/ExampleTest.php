<?php

test('the homepage redirects to the shop', function () {
    $response = $this->get(route('home'));

    $response->assertRedirect(route('shop.index'));
});
