<?php

namespace App\Http\Controllers;

use App\Support\Fakes\FakeCatalog;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class ShopController extends Controller
{
    /**
     * List active products in the catalog.
     */
    public function index(): Response
    {
        return Inertia::render('shop/index', [
            'products' => FakeCatalog::products()->where('is_active', true)->values(),
            'categories' => FakeCatalog::categories(),
        ]);
    }

    /**
     * Show a single product.
     */
    public function show(string $slug): Response|RedirectResponse
    {
        $product = FakeCatalog::findBySlug($slug);

        if ($product === null) {
            return to_route('shop.index');
        }

        return Inertia::render('shop/show', [
            'product' => $product,
        ]);
    }
}
