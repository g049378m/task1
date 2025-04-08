<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-semibold text-gray-800 leading-tight">
            Order #{{ $order->id }} Details
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 space-y-6">

            <div class="flex justify-end">
                <a href="{{ route('orders.addPizzasForm', $order) }}" class="bg-blue-600 text-white px-4 py-2 rounded text-sm hover:bg-blue-700">
                    Add Pizzas to Order
                </a>
            </div>

            <div class="bg-white p-4 rounded shadow">
                <h3 class="font-semibold mb-2">Order Information</h3>
                <p><strong>Order Type:</strong> {{ ucfirst($order->type) }}</p>
                <p><strong>Delivery Charge:</strong> £{{ number_format($order->delivery_charge, 2) }}</p>
            </div>

            <div class="bg-white p-4 rounded shadow">
                <h3 class="font-semibold mb-2">Pizzas</h3>

                @forelse ($order->orderPizzas as $orderPizza)
                
                @php
                        $pizza = $orderPizza->pizza;
                        $toppings = $orderPizza->toppings;                      


                        
                        $baseToppings = $toppings->filter(fn($t) => (int)($t->pivot->is_extra ?? 0) === 0)->pluck('name')->toArray(); 
                        $extraToppings = $toppings->filter(fn($t) => (int)($t->pivot->is_extra ?? 0) === 1)->pluck('name')->toArray(); 


                        $sizePrice = $pizza[$orderPizza->size . '_price'] ?? 0;
                        $extraCount = count($extraToppings);
                        $itemTotal = $sizePrice + ($extraCount * 0.85); 
                    @endphp

                    <div class="mb-4 border-b pb-3">
                        <div class="flex justify-between items-center">
                            <p><strong>{{ $pizza->name }}</strong> ({{ ucfirst($orderPizza->size) }})</p>
                            <a href="{{ route('orders.customisePizzaForm', [$order, $orderPizza]) }}"
                               class="text-sm bg-gray-200 hover:bg-gray-300 text-black px-2 py-1 rounded">
                                Customise
                            </a>
                        </div>

                        @if (!empty($baseToppings))
                            <p class="text-sm text-gray-600">Base Toppings: {{ implode(', ', $baseToppings) }}</p>
                        @endif

                        @if (!empty($extraToppings))
                            <p class="text-sm text-gray-600">Extra Toppings (85p): {{ implode(', ', $extraToppings) }}</p>
                        @endif

                        <p class="text-sm font-semibold mt-1">Item Total: £{{ number_format($itemTotal, 2) }}</p>
                    </div>
                @empty
                    <p>No pizzas in this order yet.</p>
                @endforelse
            </div>

            
            <div class="bg-white p-4 rounded shadow">
                <h3 class="font-semibold mb-2">Total</h3>

                @php
                    $grandTotal = $order->delivery_charge;

                    foreach ($order->orderPizzas as $orderPizza) {
                        $pizza = $orderPizza->pizza;
                        $basePrice = $pizza[$orderPizza->size . '_price'] ?? 0;
                        $extraCount = $orderPizza->toppings->filter(fn($t) => (int)$t->pivot->is_extra === 1)->count();
                        $grandTotal += $basePrice + ($extraCount * 0.85);
                    }
                @endphp

                <p><strong>Grand Total:</strong> £{{ number_format($grandTotal, 2) }}</p>

                {{-- Submit or Submitted Status --}}
                @if (!$order->submitted_at)
                    @if ($order->orderPizzas->count() > 0)
                        <form action="{{ route('orders.review', $order) }}" method="GET" class="mt-4">
                            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                                Review and Submit
                            </button>
                        </form>
                    @else
                        <div class="text-500 mt-4">
                        <! –– Bit artik bit a.q. Laraveli––> 
                        </div>
                    @endif
                @else
                    <div class="mt-4 text-green-700 font-semibold">
                        This order was submitted on {{ \Carbon\Carbon::parse($order->submitted_at)->format('d M Y') }}
                    </div>
                @endif
            </div>

        
            
        </div>
    </div>
</x-app-layout>
