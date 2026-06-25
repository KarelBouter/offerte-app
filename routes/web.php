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

Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::get('/', fn () => redirect()->route('admin.products.index'))->name('dashboard');
    Route::get('/products', \App\Livewire\Admin\Products\Index::class)->name('products.index');
    Route::get('/products/create', \App\Livewire\Admin\Products\Form::class)->name('products.create');
    Route::get('/products/{product}/edit', \App\Livewire\Admin\Products\Form::class)->name('products.edit');
});

require __DIR__.'/auth.php';
