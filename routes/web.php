<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::prefix('verkoper')->name('verkoper.')->middleware(['auth', 'verified', 'role:admin,verkoper'])->group(function () {
    Route::get('/', fn () => redirect()->route('verkoper.quotes.index'))->name('dashboard');
    Route::get('/offertes', \App\Livewire\Verkoper\Quotes\Index::class)->name('quotes.index');
    Route::get('/offertes/create', \App\Livewire\Verkoper\Quotes\Create::class)->name('quotes.create');
    Route::get('/offertes/{quote}/pdf', \App\Http\Controllers\QuotePdfController::class)->name('quotes.pdf');
    Route::get('/offertes/{quote}/edit', \App\Livewire\Verkoper\Quotes\Create::class)->name('quotes.edit');
    Route::get('/offertes/{quote}', \App\Livewire\Verkoper\Quotes\Show::class)->name('quotes.show');
});

Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::get('/', fn () => redirect()->route('admin.products.index'))->name('dashboard');
    Route::get('/products', \App\Livewire\Admin\Products\Index::class)->name('products.index');
    Route::get('/products/create', \App\Livewire\Admin\Products\Form::class)->name('products.create');
    Route::get('/products/{product}/edit', \App\Livewire\Admin\Products\Form::class)->name('products.edit');
    Route::get('/dependencies', \App\Livewire\Admin\Dependencies\Index::class)->name('dependencies.index');
    Route::get('/settings', \App\Livewire\Admin\Settings\Index::class)->name('settings.index');
});

require __DIR__.'/auth.php';
