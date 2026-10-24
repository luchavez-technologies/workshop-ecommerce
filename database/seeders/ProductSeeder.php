<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

/**
 * Seeds the `products` table once you've built the Product model and
 * migration (run CategorySeeder first — this looks categories up by slug).
 * Uncomment the `ProductSeeder::class` call in DatabaseSeeder::run() to
 * try it. The data here mirrors app/Support/Fakes/FakeCatalog.php, so once
 * this seeder runs cleanly you have a real-data replacement ready to swap
 * into the shop controllers.
 */
class ProductSeeder extends Seeder
{
    public function run(): void
    {
        foreach (self::products() as $product) {
            $categorySlug = $product['category_slug'];
            unset($product['category_slug']);

            Product::query()->updateOrCreate(
                ['slug' => $product['slug']],
                [
                    ...$product,
                    'category_id' => Category::query()->where('slug', $categorySlug)->value('id'),
                ],
            );
        }
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public static function products(): array
    {
        return [
            ['category_slug' => 'apparel', 'name' => 'Classic Cotton Tee', 'slug' => 'classic-cotton-tee', 'description' => 'A soft, breathable everyday t-shirt.', 'price' => 349.00, 'stock_quantity' => 42, 'image_path' => null, 'is_active' => true],
            ['category_slug' => 'apparel', 'name' => 'Denim Jacket', 'slug' => 'denim-jacket', 'description' => 'Washed denim jacket with a relaxed fit.', 'price' => 1899.00, 'stock_quantity' => 15, 'image_path' => null, 'is_active' => true],
            ['category_slug' => 'electronics', 'name' => 'Wireless Earbuds', 'slug' => 'wireless-earbuds', 'description' => 'Bluetooth earbuds with noise isolation.', 'price' => 2499.00, 'stock_quantity' => 25, 'image_path' => null, 'is_active' => true],
            ['category_slug' => 'electronics', 'name' => 'Portable Power Bank', 'slug' => 'portable-power-bank', 'description' => '10,000mAh fast-charging power bank.', 'price' => 1299.00, 'stock_quantity' => 60, 'image_path' => null, 'is_active' => true],
            ['category_slug' => 'electronics', 'name' => 'Mechanical Keyboard', 'slug' => 'mechanical-keyboard', 'description' => 'Hot-swappable 65% mechanical keyboard.', 'price' => 3499.00, 'stock_quantity' => 8, 'image_path' => null, 'is_active' => true],
            ['category_slug' => 'home-living', 'name' => 'Ceramic Mug Set', 'slug' => 'ceramic-mug-set', 'description' => 'Set of 4 handmade ceramic mugs.', 'price' => 899.00, 'stock_quantity' => 30, 'image_path' => null, 'is_active' => true],
            ['category_slug' => 'home-living', 'name' => 'Scented Soy Candle', 'slug' => 'scented-soy-candle', 'description' => 'Hand-poured soy candle, 8oz tin.', 'price' => 499.00, 'stock_quantity' => 50, 'image_path' => null, 'is_active' => true],
            ['category_slug' => 'home-living', 'name' => 'Linen Throw Pillow', 'slug' => 'linen-throw-pillow', 'description' => 'Natural linen cover, 18x18in insert included.', 'price' => 749.00, 'stock_quantity' => 20, 'image_path' => null, 'is_active' => true],
            ['category_slug' => 'books', 'name' => 'The Pragmatic Programmer', 'slug' => 'the-pragmatic-programmer', 'description' => 'Classic guide to software craftsmanship.', 'price' => 1450.00, 'stock_quantity' => 12, 'image_path' => null, 'is_active' => true],
            ['category_slug' => 'books', 'name' => 'Clean Architecture', 'slug' => 'clean-architecture', 'description' => "Robert C. Martin's guide to software structure.", 'price' => 1550.00, 'stock_quantity' => 0, 'image_path' => null, 'is_active' => true],
        ];
    }
}
