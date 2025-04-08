<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'delivery_charge',
        'total',
    ];

    /**
     * Get the user that owns the order.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pizzas()
    {
        return $this->belongsToMany(Pizza::class, 'order_pizzas') 
                    ->withPivot('size') 
                    ->withTimestamps();
    }

    public function toppings()
    {
        return $this->belongsToMany(Topping::class, 'order_pizza_topping')
                    ->withPivot('pizza_id', 'is_extra')
                    ->withTimestamps();
    }

    public function orderPizzas()
    {
        return $this->hasMany(OrderPizza::class);
    }

}
