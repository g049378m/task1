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

    
    public function orderPizzas()
    {
        return $this->hasMany(OrderPizza::class); 
    }

    
    public function toppings()
    {
        return $this->belongsToMany(Topping::class, 'pizza_topping')->withTimestamps();
    }

    
    public function toppingsInOrder($orderId, $pizzaRowId)
    {
        return $this->belongsToMany(Topping::class, 'order_pizza_topping')
                    ->wherePivot('order_id', $orderId)
                    ->wherePivot('pizza_row_id', $pizzaRowId) 
                    ->withPivot('is_extra')
                    ->withTimestamps();
    }
}
