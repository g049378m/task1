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

    public function orders()
    {
        return $this->belongsToMany(Order::class)->withPivot('quantity')->withTimestamps();
    }

    public function toppings()
    {
        return $this->belongsToMany(Topping::class, 'pizza_topping')->withTimestamps();
    }
    

    public function toppingsInOrder($orderId)
    {
        return $this->belongsToMany(Topping::class, 'order_pizza_topping')
                    ->wherePivot('order_id', $orderId)
                    ->withPivot('is_extra')
                    ->withTimestamps();
    }
    

}

