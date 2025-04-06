<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Order #{{ $order->id }} Details
        </h2>
    </x-slot>

    <div class="py-12 max-w-4xl mx-auto space-y-4">
        <p><strong>Order Type:</strong> {{ ucfirst($order->type) }}</p>
        <p><strong>Delivery Charge:</strong> £{{ number_format($order->delivery_charge, 2) }}</p>
        <p><strong>Total:</strong> £{{ number_format($order->total, 2) }}</p>

        <div class="mt-4">
            <a href="{{ route('orders.addPizzasForm', $order) }}" class="text-indigo-600 hover:underline">Add Pizzas</a>
        </div>
    </div>
</x-app-layout>
