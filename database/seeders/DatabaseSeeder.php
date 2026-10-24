<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        User::factory()->admin()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
        ]);

        // Workshop checkpoints: uncomment each line below as you finish the
        // matching migration + model. If a field doesn't match your schema,
        // the seeder will fail loudly — that's the point. Run in order,
        // each one depends on the data the previous one creates.
        // $this->call(CategorySeeder::class);
        // $this->call(ProductSeeder::class);
        // $this->call(CartSeeder::class);
        // $this->call(OrderSeeder::class);
    }
}
