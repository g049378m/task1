<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderPizza extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'pizza_id',
        'size', 
        
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function pizza()
    {
        return $this->belongsTo(Pizza::class);
    }

    public function toppings()
    {
        return $this->belongsToMany(Topping::class, 'order_pizza_topping', 'pizza_row_id', 'topping_id')
                    ->withPivot('is_extra')
                    ->withTimestamps();
    }

}
