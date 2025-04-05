<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pizza Menu') }}
        </h2>
    </x-slot>

    <div class="py-12 max-w-4xl mx-auto space-y-4">
        @foreach ($pizzas as $pizza)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold">{{ $pizza->name }}</h3>
                <p class="text-sm text-gray-600 mb-2">{{ $pizza->description }}</p>

                <p class="text-sm"><strong>Prices:</strong>
                    Small £{{ number_format($pizza->small_price, 2) }},
                    Medium £{{ number_format($pizza->medium_price, 2) }},
                    Large £{{ number_format($pizza->large_price, 2) }}
                </p>
            </div>
        @endforeach
    </div>
</x-app-layout>
