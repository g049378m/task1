<h2>Customise {{ $pizza->name }}</h2>

<form method="POST" action="{{ route('orders.saveCustomisation', [$order, $pizza]) }}">
    @csrf

    @foreach ($availableToppings as $topping)
        <div>
            <label>
                <input 
                    type="checkbox" 
                    name="toppings[]" 
                    value="{{ $topping->id }}"
                    {{ in_array($topping->id, $selectedToppings) ? 'checked' : '' }}
                >
                    {{ $topping->name }}
            </label>
        </div>
    @endforeach

    <button type="submit">Save Toppings</button>
</form>
