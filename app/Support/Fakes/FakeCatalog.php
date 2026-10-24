<?php

namespace App\Support\Fakes;

use Illuminate\Support\Collection;

/**
 * Stand-in for the `categories`/`products` tables described in the README's
 * ERD. Every array below is shaped exactly like the Eloquent attributes
 * (plus eager-loaded relations) those tables will expose, so swapping a
 * call site for `Product::with('category')->get()` later is a drop-in
 * replacement — not a redesign.
 *
 * TODO(workshop): delete this class once `products`/`categories` migrations
 * and models exist, and replace every call site with real Eloquent queries.
 */
class FakeCatalog
{
    /**
     * @return array{id: int, name: string, slug: string}
     */
    protected static function category(int $id, string $name, string $slug): array
    {
        return ['id' => $id, 'name' => $name, 'slug' => $slug];
    }

    /**
     * @return Collection<int, array<string, mixed>>
     */
    public static function categories(): Collection
    {
        return collect([
            self::category(1, 'Apparel', 'apparel'),
            self::category(2, 'Electronics', 'electronics'),
            self::category(3, 'Home & Living', 'home-living'),
            self::category(4, 'Books', 'books'),
        ]);
    }

    /**
     * @return Collection<int, array<string, mixed>>
     */
    public static function products(): Collection
    {
        $categories = self::categories()->keyBy('id');

        return collect([
            ['id' => 1, 'category_id' => 1, 'name' => 'Classic Cotton Tee', 'slug' => 'classic-cotton-tee', 'description' => 'A soft, breathable everyday t-shirt.', 'price' => 349.00, 'stock_quantity' => 42, 'image_path' => null, 'is_active' => true],
            ['id' => 2, 'category_id' => 1, 'name' => 'Denim Jacket', 'slug' => 'denim-jacket', 'description' => 'Washed denim jacket with a relaxed fit.', 'price' => 1899.00, 'stock_quantity' => 15, 'image_path' => null, 'is_active' => true],
            ['id' => 3, 'category_id' => 2, 'name' => 'Wireless Earbuds', 'slug' => 'wireless-earbuds', 'description' => 'Bluetooth earbuds with noise isolation.', 'price' => 2499.00, 'stock_quantity' => 25, 'image_path' => null, 'is_active' => true],
            ['id' => 4, 'category_id' => 2, 'name' => 'Portable Power Bank', 'slug' => 'portable-power-bank', 'description' => '10,000mAh fast-charging power bank.', 'price' => 1299.00, 'stock_quantity' => 60, 'image_path' => null, 'is_active' => true],
            ['id' => 5, 'category_id' => 2, 'name' => 'Mechanical Keyboard', 'slug' => 'mechanical-keyboard', 'description' => 'Hot-swappable 65% mechanical keyboard.', 'price' => 3499.00, 'stock_quantity' => 8, 'image_path' => null, 'is_active' => true],
            ['id' => 6, 'category_id' => 3, 'name' => 'Ceramic Mug Set', 'slug' => 'ceramic-mug-set', 'description' => 'Set of 4 handmade ceramic mugs.', 'price' => 899.00, 'stock_quantity' => 30, 'image_path' => null, 'is_active' => true],
            ['id' => 7, 'category_id' => 3, 'name' => 'Scented Soy Candle', 'slug' => 'scented-soy-candle', 'description' => 'Hand-poured soy candle, 8oz tin.', 'price' => 499.00, 'stock_quantity' => 50, 'image_path' => null, 'is_active' => true],
            ['id' => 8, 'category_id' => 3, 'name' => 'Linen Throw Pillow', 'slug' => 'linen-throw-pillow', 'description' => 'Natural linen cover, 18x18in insert included.', 'price' => 749.00, 'stock_quantity' => 20, 'image_path' => null, 'is_active' => true],
            ['id' => 9, 'category_id' => 4, 'name' => 'The Pragmatic Programmer', 'slug' => 'the-pragmatic-programmer', 'description' => 'Classic guide to software craftsmanship.', 'price' => 1450.00, 'stock_quantity' => 12, 'image_path' => null, 'is_active' => true],
            ['id' => 10, 'category_id' => 4, 'name' => 'Clean Architecture', 'slug' => 'clean-architecture', 'description' => "Robert C. Martin's guide to software structure.", 'price' => 1550.00, 'stock_quantity' => 0, 'image_path' => null, 'is_active' => true],
        ])->map(fn (array $product) => [
            ...$product,
            'category' => $categories->get($product['category_id']),
        ]);
    }

    public static function find(int $id): ?array
    {
        return self::products()->firstWhere('id', $id);
    }

    public static function findBySlug(string $slug): ?array
    {
        return self::products()->firstWhere('slug', $slug);
    }
}
