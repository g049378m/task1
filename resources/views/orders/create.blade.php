<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Start a New Order') }}
        </h2>
    </x-slot>

    <div class="max-w-md mx-auto mt-8">
        <form method="POST" action="{{ route('orders.store') }}">
            @csrf

            <label for="type" class="block text-sm font-medium text-gray-700">Order Type</label>
            <select name="type" id="type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                <option value="collection">Collection</option>
                <option value="delivery">Delivery (+£5)</option>
            </select>

            <button type="submit" class="mt-4 px-4 py-2 bg-blue-600 text-white rounded">
                Start Order
            </button>
        </form>
    </div>
</x-app-layout>
