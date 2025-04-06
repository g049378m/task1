<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Toppings') }}
        </h2>
    </x-slot>

    <div class="py-12 max-w-4xl mx-auto space-y-4">
        @forelse ($toppings as $topping)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold">{{ $topping->name }}</h3>
            </div>
        @empty
            <p class="text-gray-600">No toppings found.</p>
        @endforelse
    </div>
</x-app-layout>
