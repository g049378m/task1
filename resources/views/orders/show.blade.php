<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-semibold leading-tight text-gray-800">
            Order #{{ $order->id }} Details
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 space-y-6">

            <!-- Add Pizza Button (right aligned) -->
            <div class="flex justify-end">
                <a href="{{ route('orders.addPizzasForm', $order) }}" class="bg-blue-600 text-white px-4 py-2 rounded text-sm hover:bg-blue-700">
                    ➕ Add Pizzas to Order
                </a>
            </div>

            <!-- Order Info -->
            <div class="bg-white p-4 rounded shadow">
                <h3 class="font-semibold mb-2">Order Information</h3>
                <p><strong>Order Type:</strong> {{ ucfirst($order->type) }}</p>
                <p><strong>Delivery Charge:</strong> £{{ number_format($order->delivery_charge, 2) }}</p>
            </div>

            <!-- Pizza List -->
<div class="bg-white p-4 rounded shadow">
    <h3 class="font-semibold mb-2">Pizzas</h3>

    @forelse ($order->pizzas as $pizza)
        <div class="mb-4 border-b pb-2">
            <div class="flex justify-between items-center">
                <p><strong>{{ $pizza->name }}</strong> x {{ $pizza->pivot->quantity }}</p>

                <a href="{{ route('orders.customisePizzaForm', [$order, $pizza]) }}"
                   class="text-sm bg-gray-200 hover:bg-gray-300 text-black px-2 py-1 rounded">
                    Customise
                </a>
            </div>

            @php
                $toppings = $pizza->toppingsInOrder($order->id)->get(); 
                $baseToppings = $toppings->filter(fn($t) => !$t->pivot->is_extra)->pluck('name')->toArray(); 
                $extraToppings = $toppings->filter(fn($t) => $t->pivot->is_extra)->pluck('name')->toArray(); 


                $basePrice = $pizza->medium_price; 
                $extraCount = count($extraToppings);
                $pizzaTotal = ($basePrice + ($extraCount * 0.85)) * $pizza->pivot->quantity;
            @endphp

            @if (!empty($baseToppings))
                <p class="text-sm text-gray-600">Base Toppings: {{ implode(', ', $baseToppings) }}</p>
            @endif

            @if (!empty($extraToppings))
                <p class="text-sm text-gray-600">Extra Toppings (85p): {{ implode(', ', $extraToppings) }}</p>
            @endif

            <p class="text-sm text-gray-800 mt-1 font-semibold">Total: £{{ number_format($pizzaTotal, 2) }}</p>
        </div>
    @empty
        <p>No pizzas in this order yet.</p>
    @endforelse
</div>

            <!-- Total Summary -->
            <div class="bg-white p-4 rounded shadow">
                <h3 class="font-semibold mb-2">Total</h3>

                @php
                    $total = $order->delivery_charge;

                    foreach ($order->pizzas as $pizza) {
                        $quantity = $pizza->pivot->quantity;
                        $basePrice = $pizza->medium_price;

                        

                        $toppings = $pizza->toppingsInOrder($order->id)->get(); 
                        $extraCount = $toppings->filter(fn($t) => $t->pivot->is_extra)->count(); 

                        
                        $total += ($basePrice + ($extraCount * 0.85)) * $quantity;
                    }
                @endphp

                <p><strong>Grand Total:</strong> £{{ number_format($total, 2) }}</p>
            </div>

        </div>
    </div>
</x-app-layout>
