<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Topping;

class ToppingController extends Controller
{
    public function index() 
    {
        $toppings = Topping::all();
        return view('toppings.index', compact('toppings'));
    }

}
