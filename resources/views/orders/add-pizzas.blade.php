<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-semibold leading-tight text-gray-800">
            Add Pizzas to Order #{{ $order->id }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4">
            <form method="POST" action="{{ route('orders.addPizzas', $order) }}">
                @csrf

                @foreach ($pizzas as $pizza)
                    <div class="mb-4 border-b pb-4">
                        <p><strong>{{ $pizza->name }}</strong> — {{ $pizza->description }}</p>

                        <div class="mt-2 space-x-4">
                            <label>
                                <input type="radio" name="pizzas[{{ $pizza->id }}][size]" value="small">
                                Small (£{{ number_format($pizza->small_price, 2) }})
                            </label>
                            <label>
                                <input type="radio" name="pizzas[{{ $pizza->id }}][size]" value="medium">
                                Medium (£{{ number_format($pizza->medium_price, 2) }})
                            </label>
                            <label>
                                <input type="radio" name="pizzas[{{ $pizza->id }}][size]" value="large">
                                Large (£{{ number_format($pizza->large_price, 2) }})
                            </label>
                        </div>
                    </div>
                @endforeach

                <div class="mt-6">
                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                        Add Selected Pizzas
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
