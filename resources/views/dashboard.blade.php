<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-semibold text-gray-800 leading-tight">
            Dashboard
        </h2>
    </x-slot>

    <div class="py-6 max-w-5xl mx-auto px-4 space-y-6">
        <div class="bg-white p-4 rounded shadow">
            <h3 class="text-lg font-semibold mb-4">Your Previous Orders</h3>

            @forelse ($orders as $order)
                <div class="border-b pb-3 mb-4">
                    <p><strong>Order #{{ $order->id }}</strong></p>
                    <p><span class="font-medium">Submitted:</span> {{ \Carbon\Carbon::parse($order->submitted_at)->format('d M Y') }}</p>

                    @php
                        $total = $order->delivery_charge;
                        foreach ($order->orderPizzas as $orderPizza) {
                            $pizza = $orderPizza->pizza;
                            $price = $pizza[$orderPizza->size . '_price'] ?? 0;
                            $extras = $orderPizza->toppings->where('pivot.is_extra', true)->count();
                            $total += $price + ($extras * 0.85);
                        }
                    @endphp

                    <p><span class="font-medium">Total:</span> £{{ number_format($total, 2) }}</p>

                    <div class="mt-2 flex gap-4">
                        <a href="{{ route('orders.show', $order) }}"
                           class="bg-blue-500 text-white px-3 py-1 rounded text-sm hover:bg-blue-600">
                            View Order
                        </a>

                        <form method="POST" action="{{ route('orders.reorder', $order) }}" class="inline"
                              onsubmit="return confirm('Are you sure you want to reorder this order?');">
                            @csrf
                            <button type="submit"
                                    class="bg-green-600 text-white px-3 py-1 rounded text-sm hover:bg-green-700">
                                Re-order
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <p class="text-gray-600">You dont have any previousorder yet.</p>
            @endforelse
        </div>
    </div>
</x-app-layout>
