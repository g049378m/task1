<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PizzaController;
use App\Http\Controllers\ToppingController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderPizzaToppingController;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('pizzas.index');
});
Route::get('/pizza/create', [PizzaController::class, 'create'])->name('pizza.create');
Route::post('/pizza', [PizzaController::class, 'store'])->name('pizza.store');
Route::get('/pizzas', [PizzaController::class, 'index'])->name('pizzas.index');
Route::get('/toppings', [ToppingController::class, 'index'])->name('toppings.index');


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');    
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/orders/create', [OrderController::class, 'create'])->name('orders.create');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/{order}/add-pizzas', [OrderController::class, 'addPizzasForm'])->name('orders.addPizzasForm');
    Route::post('/orders/{order}/add-pizzas', [OrderController::class, 'addPizzas'])->name('orders.addPizzas');
    Route::get('/orders/{order}/pizza/{pizza}/customise', [OrderController::class, 'customisePizzaForm'])->name('orders.customisePizzaForm');
    Route::post('/orders/{order}/pizza/{pizza}/customise', [OrderController::class, 'saveCustomisation'])->name('orders.saveCustomisation');
});

require __DIR__.'/auth.php';
