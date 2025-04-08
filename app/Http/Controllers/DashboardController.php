<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;

class DashboardController extends Controller
{
    public function index()
    {
        $orders = Order::with('orderPizzas.pizza', 'orderPizzas.toppings')
            ->where('user_id', Auth::id())
            ->whereNotNull('submitted_at')
            ->orderByDesc('submitted_at')
            ->get();

        return view('dashboard', compact('orders'));
    }
}
