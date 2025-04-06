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
            'description' => 'Cheese, tomato sauce',
            'small_price' => 8,
            'medium_price' => 9,
            'large_price' => 12,
        ]);

        Pizza::create([
            'name' => 'Meat Feast',
            'description' => 'Pepperoni, ham, chicken, minced beef, sausage, bacon',
            'small_price' => 11,
            'medium_price' => 14.50,
            'large_price' => 16.50,
        ]);

        Pizza::create([
            'name' => 'Veggie Deluxe',
            'description' => 'Peppers, onions, mushrooms, olives, sweetcorn',
            'small_price' => 10,
            'medium_price' => 13,
            'large_price' => 15,
        ]);


        Pizza::create([
            'name' => 'Make Mine Hot',
            'description' => 'Chicken, onions, green peppers, jalapeno peppers',
            'small_price' => 11,
            'medium_price' => 13,
            'large_price' => 15,
        ]);
    }
}
