<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Maya Checkout
    |--------------------------------------------------------------------------
    |
    | Credentials and base URL for Maya's Checkout API. Use the sandbox
    | public/secret keys from https://developers.maya.ph/reference/sandbox-credentials-and-cards
    | while developing, and swap in production keys + base URL at launch.
    |
    */

    'public_key' => env('MAYA_PUBLIC_KEY'),
    'secret_key' => env('MAYA_SECRET_KEY'),
    'base_url' => env('MAYA_BASE_URL', 'https://pg-sandbox.paymaya.com'),

    'redirect' => [
        'success' => env('MAYA_REDIRECT_SUCCESS', '/checkout/success'),
        'failure' => env('MAYA_REDIRECT_FAILURE', '/checkout/cancel'),
        'cancel' => env('MAYA_REDIRECT_CANCEL', '/checkout/cancel'),
    ],

];
