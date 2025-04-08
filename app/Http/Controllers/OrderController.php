<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Pizza;
use Illuminate\Support\Facades\DB;
use App\Models\Topping;
use App\Models\OrderPizza;

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

        if ($order->submitted_at) {
            return redirect()->route('orders.show', $order)->with('error', 'You cannot edit a submitted order.');
        }

        $pizzas = Pizza::all();
        return view('orders.add-pizzas', compact('order', 'pizzas'));
    }


    /**
     * Seçili pizzaları siparişte tutmak icin gerekli fonksiyon
     */
    
     public function customisePizzaForm(Order $order, \App\Models\OrderPizza $orderPizza)
     {
         $this->authorize('update', $order);
     
         $availableToppings = Topping::all();
     
         $selectedToppings = $orderPizza->toppings->pluck('id')->toArray();
         $extraToppings = $orderPizza->toppings->filter(fn($t) => $t->pivot->is_extra)->pluck('id')->toArray();
     
         return view('orders.customise-pizza', compact('order', 'orderPizza', 'availableToppings', 'selectedToppings', 'extraToppings'));
     }
     
    
    public function addPizzas(Request $request, Order $order)
    {
        $this->authorize('update', $order);

        if ($order->submitted_at) {
            return redirect()->route('orders.show', $order)->with('error', 'You cannot edit a submitted order.');
        }
    
        foreach ($request->input('pizzas', []) as $pizzaId => $data) {
            if (!isset($data['size'])) {
                continue;
            }
    
            $pizza = Pizza::findOrFail($pizzaId);
            $size = $data['size'];
    
            $orderPizza = \App\Models\OrderPizza::create([
                'order_id' => $order->id,
                'pizza_id' => $pizzaId,
                'size' => $size,
            ]);
    
            $baseToppingIds = $pizza->toppings()->pluck('toppings.id')->toArray();
    
            foreach ($baseToppingIds as $toppingId) {
                \DB::table('order_pizza_topping')->insert([
                    'order_id'     => $order->id,
                    'pizza_id'     => $pizzaId,
                    'pizza_row_id' => $orderPizza->id, 
                    'topping_id'   => $toppingId,
                    'is_extra'     => false,
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ]);
            }
        }

        $this->recalculateTotal($order);

    
        return redirect()->route('orders.show', $order)->with('success', 'Pizzas added to your order.');
    }
    
    

    public function saveCustomisation(Request $request, Order $order, OrderPizza $orderPizza)
    {
        $this->authorize('update', $order);

    if ($order->submitted_at) {
        return redirect()->route('orders.show', $order)->with('error', 'You cannot customise a submitted order.');
    }

        \DB::table('order_pizza_topping')
            ->where('order_id', $order->id)
            ->where('pizza_id', $orderPizza->pizza_id)
            ->where('pizza_row_id', $orderPizza->id)
            ->delete();

        $selectedToppings = $request->input('toppings', []);

    
        $baseToppingIds = $orderPizza->pizza->toppings->pluck('id')->toArray();

        foreach ($selectedToppings as $toppingId) {
            \DB::table('order_pizza_topping')->insert([
                'order_id'      => $order->id,
                'pizza_id'      => $orderPizza->pizza_id,
                'pizza_row_id'  => $orderPizza->id,
                'topping_id'    => $toppingId,
                'is_extra'      => !in_array((int)$toppingId, $baseToppingIds), 
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);
        }

        $this->recalculateTotal($order);

        return redirect()->route('orders.show', $order)->with('success', 'Pizza customisation updated.');
    }

    public function review(Order $order)
    {
        $this->authorize('view', $order);

        if ($order->orderPizzas->count() === 0) {
            return redirect()->route('orders.show', $order)->with('error', 'You must add pizzas before reviewing.');
        }

        return view('orders.review', compact('order'));
    }

    public function reorder(Request $request, Order $order)
    {
        $this->authorize('view', $order);
    
        $newOrder = Order::create([
            'user_id' => auth()->id(),
            'type' => $order->type,
            'delivery_charge' => $order->delivery_charge,
            'total' => 0,
            'submitted_at' => null, 
        ]);
    
        foreach ($order->orderPizzas as $oldOrderPizza) {
            $newOrderPizza = OrderPizza::create([
                'order_id' => $newOrder->id,
                'pizza_id' => $oldOrderPizza->pizza_id,
                'size' => $oldOrderPizza->size,
            ]);
    
            foreach ($oldOrderPizza->toppings as $topping) {
                $newOrderPizza->toppings()->attach($topping->id, [
                    'order_id'     => $newOrder->id,
                    'pizza_id'     => $newOrderPizza->pizza_id,
                    'pizza_row_id' => $newOrderPizza->id,
                    'is_extra'     => (bool) $topping->pivot->is_extra,
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ]);
            }
        }
    
        $this->recalculateTotal($newOrder);
    
        return redirect()->route('orders.review', $newOrder)->with('success', 'Order duplicated. Please review and submit.');
    }
    



   

    private function recalculateTotal(Order $order)
    {
        $baseTotal = 0;
        $extraToppingCount = 0;

        foreach ($order->orderPizzas as $orderPizza) {
            $pizza = $orderPizza->pizza;

            
            $size = $orderPizza->size;
            $basePrice = match ($size) {
                'small' => $pizza->small_price,
                'medium' => $pizza->medium_price,
                'large' => $pizza->large_price,
                default => $pizza->medium_price,
            };

            $baseTotal += $basePrice;

            
            $extraToppings = $orderPizza->toppings->filter(fn($t) => $t->pivot->is_extra)->count();
            $extraToppingCount += $extraToppings;
        }

        $total = $baseTotal + ($extraToppingCount * 0.85) + $order->delivery_charge;

        $order->update(['total' => $total]);
    }

    public function submit(Order $order)
    {
        $this->authorize('update', $order);

        if ($order->submitted_at) {
            return redirect()->route('orders.show', $order)->with('info', 'Order was already submitted.');
        }

        $order->submitted_at = now();
        $order->save();

        return redirect()->route('dashboard')->with('success', 'Order submitted successfully!');

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
