<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-semibold leading-tight text-gray-800">
            Review Your Order #{{ $order->id }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-4xl mx-auto px-4 space-y-6">

        <!-- Order Info -->
        <div class="bg-white p-4 rounded shadow">
            <h3 class="font-semibold mb-2">Order Information</h3>
            <p><strong>Order Type:</strong> {{ ucfirst($order->type) }}</p>
            <p><strong>Delivery Charge:</strong> £{{ number_format($order->delivery_charge, 2) }}</p>
        </div>

        <!-- Pizza Summary -->
        <div class="bg-white p-4 rounded shadow">
            <h3 class="font-semibold mb-2">Pizzas in Your Order</h3>

            @forelse ($order->orderPizzas as $orderPizza)
                @php
                    $pizza = $orderPizza->pizza;
                    $toppings = $orderPizza->toppings;
                    $baseToppings = $toppings->filter(fn($t) => (int)($t->pivot->is_extra) === 0)->pluck('name')->toArray();
                    $extraToppings = $toppings->filter(fn($t) => (int)($t->pivot->is_extra) === 1)->pluck('name')->toArray();
                    $sizePrice = $pizza[$orderPizza->size . '_price'] ?? 0;
                    $extraCount = count($extraToppings);
                    $itemTotal = $sizePrice + ($extraCount * 0.85);
                @endphp

                <div class="mb-4 border-b pb-3">
                    <p><strong>{{ $pizza->name }}</strong> ({{ ucfirst($orderPizza->size) }})</p>

                    @if (!empty($baseToppings))
                        <p class="text-sm text-gray-600">Base Toppings: {{ implode(', ', $baseToppings) }}</p>
                    @endif

                    @if (!empty($extraToppings))
                        <p class="text-sm text-gray-600">Extra Toppings (85p each): {{ implode(', ', $extraToppings) }}</p>
                    @endif

                    <p class="text-sm font-semibold mt-1">Item Total: £{{ number_format($itemTotal, 2) }}</p>
                </div>
            @empty
                <p class="text-red-600">⚠️ No pizzas found in this order.</p>
            @endforelse
        </div>

        <!-- Grand Total & Submit -->
        <div class="bg-white p-4 rounded shadow">
            <h3 class="font-semibold mb-2">Order Total</h3>

            @php
                $grandTotal = $order->delivery_charge;

                foreach ($order->orderPizzas as $orderPizza) {
                    $price = $orderPizza->pizza[$orderPizza->size . '_price'] ?? 0;
                    $extras = $orderPizza->toppings->filter(fn($t) => (int)($t->pivot->is_extra) === 1)->count();
                    $grandTotal += $price + ($extras * 0.85);
                }
            @endphp

            <p class="mb-4"><strong>Grand Total:</strong> £{{ number_format($grandTotal, 2) }}</p>

            @if ($order->orderPizzas->count() > 0)
                <form action="{{ route('orders.submit', $order) }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                        ✅ Confirm and Submit Order
                    </button>
                </form>
            @else
                <div class="text-red-600">⚠️ Cannot submit an empty order.</div>
            @endif
        </div>

    </div>
</x-app-layout>
