<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Customise {{ $pizza->name }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow rounded">

                <form method="POST" action="{{ route('orders.saveCustomisation', [$order, $pizza]) }}">
                    @csrf

                    <h3 class="text-lg font-medium mb-4">Select Toppings</h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-6">
                        @foreach ($availableToppings as $topping)
                            <label class="flex items-center space-x-2">
                                <input 
                                    type="checkbox" 
                                    name="toppings[]" 
                                    value="{{ $topping->id }}"
                                    class="form-checkbox text-indigo-600"
                                    {{ in_array($topping->id, $selectedToppings) ? 'checked' : '' }}
                                >
                                <span>{{ $topping->name }}</span>
                            </label>
                        @endforeach
                    </div>

                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                        Save Toppings
                    </button>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
