<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\Fakes\FakeCatalog;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    /**
     * List every product in the catalog, including inactive ones.
     */
    public function index(): Response
    {
        return Inertia::render('admin/products/index', [
            'products' => FakeCatalog::products(),
        ]);
    }
}
