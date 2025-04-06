<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Pizza;
use Illuminate\Support\Facades\DB;
use App\Models\Topping;

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

        foreach ($request->input('pizzas', []) as $pizzaId => $quantity) {
            if ((int) $quantity > 0) {
              
                $order->pizzas()->attach($pizzaId, ['quantity' => $quantity]);

              
                $pizza = Pizza::find($pizzaId);
                $baseToppingIds = $pizza->toppings()->pluck('toppings.id')->toArray();

                foreach ($baseToppingIds as $toppingId) {
                    \DB::table('order_pizza_topping')->insert([
                        'order_id'   => $order->id,
                        'pizza_id'   => $pizzaId,
                        'topping_id' => $toppingId,
                        'is_extra'   => false, 
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }

        return redirect()->route('orders.show', $order)->with('success', 'Pizzas added to your order.');
    }


    public function customisePizzaForm(Order $order, Pizza $pizza)
    {
        $this->authorize('update', $order);
    
        $availableToppings = Topping::all();
    
        $toppingsInOrder = $pizza->toppingsInOrder($order->id)->get(); // ← Important!
    
        $selectedToppings = $toppingsInOrder->pluck('id')->toArray();
        $extraToppings = $toppingsInOrder->filter(fn($t) => $t->pivot->is_extra)->pluck('id')->toArray();
    
        return view('orders.customise-pizza', compact('order', 'pizza', 'availableToppings', 'selectedToppings', 'extraToppings'));
    }
    

    

    public function saveCustomisation(Request $request, Order $order, Pizza $pizza)
    {
        $this->authorize('update', $order);
    
        $toppingIds = $request->input('toppings', []);
    
        // Detach current toppings for this order and pizza
        \DB::table('order_pizza_topping')
            ->where('order_id', $order->id)
            ->where('pizza_id', $pizza->id)
            ->delete();
    
        // Get the default toppings for this pizza
        $defaultToppings = $pizza->toppings()->pluck('toppings.id')->toArray();
    
        foreach ($toppingIds as $toppingId) {
            $isExtra = !in_array($toppingId, $defaultToppings);
    
            \DB::table('order_pizza_topping')->insert([
                'order_id' => $order->id,
                'pizza_id' => $pizza->id,
                'topping_id' => $toppingId,
                'is_extra' => $isExtra,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    
        // Recalculate the total
        $this->recalculateTotal($order);
    
        return redirect()->route('orders.show', $order)->with('success', 'Toppings updated!');
    }
    
    private function recalculateTotal(Order $order)
{
    $baseTotal = 0;
    $extraToppingCount = 0;

    foreach ($order->pizzas as $pizza) {
        // Basic price – assume medium size
        $baseTotal += $pizza->medium_price * $pizza->pivot->quantity;

        // Count extra toppings
        $extras = $pizza->toppingsInOrder($order->id)
                    ->wherePivot('is_extra', true)
                    ->count();

        $extraToppingCount += $extras * $pizza->pivot->quantity;
    }

    $total = $baseTotal + ($extraToppingCount * 0.85) + $order->delivery_charge;

    $order->update(['total' => $total]);
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
