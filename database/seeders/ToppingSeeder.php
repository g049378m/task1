<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Topping;

class ToppingSeeder extends Seeder
{
    public function run(): void
    {
        $toppings = [
            'Cheese', 'Tomato Sauce', 'Pepperoni', 'Ham', 'Chicken',
            'Minced Beef', 'Sausage', 'Bacon', 'Onions', 'Green Peppers',
            'Mushrooms', 'Sweetcorn', 'Jalapeno Peppers', 'Vegan Cheese',
            'Pineapple', 'Salami', 'Olives', 'Spicy Beef', 'Hot Dog Pieces'
        ];

        foreach ($toppings as $name) {
            Topping::create(['name' => $name]);
        }
    }
}
