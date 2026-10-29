<?php

namespace Database\Seeders;

use App\Models\Courier;
use Illuminate\Database\Seeder;

class CourierSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Courier::factory()->count(24)->create();

        Courier::factory()->create([
            'name' => 'Budiono Hadi Agung',
            'email' => 'budiono.agung@example.com',
            'level' => 3,
        ]);
    }
}
