<?php

use App\Enums\UserRole;
use App\Models\User;

test('guests are redirected to login', function () {
    $this->get(route('admin.products.index'))->assertRedirect(route('login'));
    $this->get(route('admin.orders.index'))->assertRedirect(route('login'));
});

test('customers cannot access admin pages', function () {
    $user = User::factory()->create(['role' => UserRole::Customer]);

    $this->actingAs($user)->get(route('admin.products.index'))->assertForbidden();
    $this->actingAs($user)->get(route('admin.orders.index'))->assertForbidden();
});

test('admins can access admin pages', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)->get(route('admin.products.index'))->assertOk();
    $this->actingAs($admin)->get(route('admin.orders.index'))->assertOk();
});
