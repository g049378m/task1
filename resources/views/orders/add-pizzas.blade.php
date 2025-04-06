<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Add Pizzas to Order #{{ $order->id }}
        </h2>
    </x-slot>

    <div class="py-12 max-w-4xl mx-auto">
        <form action="{{ route('orders.addPizzas', $order) }}" method="POST" class="space-y-6">
            @csrf

            @foreach ($pizzas as $pizza)
                <div class="border p-4 rounded shadow">
                    <h3 class="text-lg font-bold">{{ $pizza->name }}</h3>
                    <p class="text-sm mb-1">{{ $pizza->description }}</p>
                    <p class="text-sm">
                        Small £{{ number_format($pizza->small_price, 2) }},
                        Medium £{{ number_format($pizza->medium_price, 2) }},
                        Large £{{ number_format($pizza->large_price, 2) }}
                    </p>
                    <label for="pizza_{{ $pizza->id }}" class="block mt-2">
                        Quantity:
                        <input type="number" min="0" name="pizzas[{{ $pizza->id }}]" id="pizza_{{ $pizza->id }}" class="border rounded px-2 py-1 w-20">
                    </label>
                </div>
            @endforeach

            <x-primary-button>Add to Order</x-primary-button>
        </form>
    </div>
</x-app-layout>
