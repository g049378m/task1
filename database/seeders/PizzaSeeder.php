<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pizza;

class PizzaSeeder extends Seeder
{
    public function run(): void
    {
        Pizza::create([
            'name' => 'Margherita',
            'small_price' => 6.99,
            'medium_price' => 8.99,
            'large_price' => 10.99,
        ]);

        Pizza::create([
            'name' => 'Meat Feast',
            'small_price' => 8.99,
            'medium_price' => 11.99,
            'large_price' => 14.99,
        ]);

        Pizza::create([
            'name' => 'Veggie Deluxe',
            'small_price' => 7.49,
            'medium_price' => 9.99,
            'large_price' => 12.99,
        ]);
    }
}
