<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pizza;
use App\Models\Topping;

class PizzaSeeder extends Seeder
{

        public function run(): void
    {
        $margherita = Pizza::create([
            'name' => 'Margherita',
            'description' => 'Cheese, tomato sauce',
            'small_price' => 8,
            'medium_price' => 9,
            'large_price' => 12,
        ]);
        $this->attachToppings($margherita, ['Cheese', 'Tomato Sauce']);

        $meatFeast = Pizza::create([
            'name' => 'Meat Feast',
            'description' => 'Pepperoni, ham, chicken, minced beef, sausage, bacon',
            'small_price' => 11,
            'medium_price' => 14.50,
            'large_price' => 16.50,
        ]);
        $this->attachToppings($meatFeast, ['Pepperoni', 'Ham', 'Chicken', 'Minced Beef', 'Sausage', 'Bacon']);

        $veggie = Pizza::create([
            'name' => 'Veggie Deluxe',
            'description' => 'Peppers, onions, mushrooms, olives, sweetcorn',
            'small_price' => 10,
            'medium_price' => 13,
            'large_price' => 15,
        ]);
        $this->attachToppings($veggie, ['Mushrooms', 'Peppers', 'Onions', 'Sweetcorn', 'Tomato']);

        $hot = Pizza::create([
            'name' => 'Make Mine Hot',
            'description' => 'Chicken, onions, green peppers, jalapeno peppers',
            'small_price' => 11,
            'medium_price' => 13,
            'large_price' => 15,
        ]);
        $this->attachToppings($hot, ['Chicken', 'Onions', 'Green Peppers', 'Jalapeno Peppers']);  
        
        
    }

    public function attachToppings(Pizza $pizza, array $toppingNames): void
    {
        $toppingIds = Topping::whereIn('name', $toppingNames)->pluck('id')->toArray();
        $pizza->toppings()->sync($toppingIds);
    }
}
