<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-semibold leading-tight text-gray-800">
            Customise Pizza: {{ $orderPizza->pizza->name }} ({{ ucfirst($orderPizza->size) }})
        </h2>
    </x-slot>

    <div class="py-6 max-w-3xl mx-auto px-4">
        <form method="POST" action="{{ route('orders.saveCustomisation', [$order->id, $orderPizza->id]) }}">
            @csrf

            <h3 class="mb-4 text-md font-semibold">Toppings</h3>

            @foreach ($availableToppings as $topping)
                @php
                    $isSelected = in_array($topping->id, $selectedToppings); // All selected toppings 
                    $isExtra = in_array($topping->id, $extraToppings);       // Only extra ones
                @endphp

                <div class="flex items-center mb-2">
                    <input 
                        type="checkbox" 
                        name="toppings[]" 
                        value="{{ $topping->id }}"
                        id="topping-{{ $topping->id }}"
                        {{ $isSelected ? 'checked' : '' }}
                        class="mr-2"
                    >
                    <label for="topping-{{ $topping->id }}">
                        {{ $topping->name }} 
                        @if ($isExtra)
                            <span class="text-xs text-gray-500">+85p</span>
                        @elseif ($isSelected)
                            <span class="text-xs text-gray-500">(base)</span>
                        @endif
                    </label>
                </div>
            @endforeach


            <div class="mt-6">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Save Toppings
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
