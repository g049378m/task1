<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Pizza;


class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('orders.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:collection,delivery',
        ]);

        $deliveryCharge = $validated['type'] === 'delivery' ? 5.00 : 0.00;

        $order = Order::create([
            'user_id' => Auth::id(),
            'type' => $validated['type'],
            'delivery_charge' => $deliveryCharge,
            'total' => 0, 
        ]);

        return redirect()->route('orders.show', $order)->with('success', 'Order started!');
    }
    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        $this->authorize('view', $order);

        return view('orders.show', compact('order'));
    }

    /**
     * Siparişe pizza eklemek için formu gösteren fonksiyon????
     */
    public function addPizzasForm(Order $order)
    {
        $this->authorize('update', $order);
        $pizzas = Pizza::all();
        return view('orders.add-pizzas', compact('order', 'pizzas'));
    }

    /**
     * Seçili pizzaları siparişte tutmak icin gerekli fonksiyon
     */
    public function addPizzas(Request $request, Order $order)
    {
        $this->authorize('update', $order);

        $data = $request->input('pizzas', []);
        foreach ($data as $pizzaId => $quantity) {
            if ((int) $quantity > 0) {
                $order->pizzas()->attach($pizzaId, ['quantity' => $quantity]);
            }
        }

        return redirect()->route('orders.show', $order)->with('success', 'Pizzas added to your order.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }
}
