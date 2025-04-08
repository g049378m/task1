<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pizza extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'small_price',
        'medium_price',
        'large_price',
    ];

    // Relationship to the order_pizzas pivot model
    public function orderPizzas()
    {
        return $this->hasMany(OrderPizza::class); // One Pizza type has many individual instances in orders
    }

    // Relationship for default/base toppings defined for this pizza type
    public function toppings()
    {
        return $this->belongsToMany(Topping::class, 'pizza_topping')->withTimestamps();
    }

    // Fetch toppings related to a specific pizza in a specific order (using order_pizza_topping table)
    public function toppingsInOrder($orderId, $pizzaRowId)
    {
        return $this->belongsToMany(Topping::class, 'order_pizza_topping')
                    ->wherePivot('order_id', $orderId)
                    ->wherePivot('pizza_row_id', $pizzaRowId) // <-- new key to distinguish identical pizzas
                    ->withPivot('is_extra')
                    ->withTimestamps();
    }
}
