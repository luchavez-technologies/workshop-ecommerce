<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

/**
 * Seeds the `categories` table once you've built the Category model and
 * migration. Uncomment the `CategorySeeder::class` call in
 * DatabaseSeeder::run() to try it — if a field below doesn't match a
 * column on your migration, this will fail loudly (e.g. "Unknown column"),
 * which is exactly the point: it's a quick check that your schema matches
 * the README's ERD before you move on to products.
 */
class CategorySeeder extends Seeder
{
    public function run(): void
    {
        foreach (self::categories() as $category) {
            Category::query()->updateOrCreate(['slug' => $category['slug']], $category);
        }
    }

    /**
     * @return array<int, array{name: string, slug: string}>
     */
    public static function categories(): array
    {
        return [
            ['name' => 'Apparel', 'slug' => 'apparel'],
            ['name' => 'Electronics', 'slug' => 'electronics'],
            ['name' => 'Home & Living', 'slug' => 'home-living'],
            ['name' => 'Books', 'slug' => 'books'],
        ];
    }
}
