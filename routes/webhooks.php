<?php

use App\Http\Controllers\MayaWebhookController;
use Illuminate\Support\Facades\Route;

// Public, no CSRF (excluded in bootstrap/app.php) — Maya posts here directly.
Route::post('webhooks/maya', [MayaWebhookController::class, 'handle'])->name('webhooks.maya');
