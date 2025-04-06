<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add Pizzas to Order') }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-3xl mx-auto">
        <form method="POST" action="{{ route('orders.addPizzas', $order) }}">
            @csrf

            <div class="space-y-4">
                @foreach ($pizzas as $pizza)
                    <div class="border p-4 rounded-md shadow-sm">
                        <h3 class="font-bold text-lg">{{ $pizza->name }}</h3>
                        <p class="text-sm text-gray-600">{{ $pizza->description ?? 'No description.' }}</p>
                        <p class="text-sm text-gray-700 mb-2">
                            Small £{{ $pizza->small_price }}, Medium £{{ $pizza->medium_price }}, Large £{{ $pizza->large_price }}
                        </p>
                        <label for="pizza_{{ $pizza->id }}" class="block text-sm font-medium text-gray-700">Quantity:</label>
                        <input type="number" name="pizzas[{{ $pizza->id }}]" id="pizza_{{ $pizza->id }}" min="0" class="mt-1 block w-20 border-gray-300 rounded-md shadow-sm">
                    </div>
                @endforeach
            </div>

            <div class="mt-6">
                <x-primary-button>Add to Order</x-primary-button>
            </div>
        </form>
    </div>
</x-app-layout>
