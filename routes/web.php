<?php

use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('login'));

Route::get('/dashboard', fn () => redirect()->route('login'))
    ->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profiel', \App\Livewire\Profile\Edit::class)->name('profile.edit');
});

Route::prefix('verkoper')->name('verkoper.')->middleware(['auth', 'verified', 'role:admin,verkoper'])->group(function () {
    Route::get('/', \App\Livewire\Verkoper\Dashboard::class)->name('dashboard');
    Route::get('/offertes', \App\Livewire\Verkoper\Quotes\Index::class)->name('quotes.index');
    Route::get('/offertes/create', \App\Livewire\Verkoper\Quotes\Create::class)->name('quotes.create');
    Route::get('/offertes/{quote}/pdf', \App\Http\Controllers\QuotePdfController::class)->name('quotes.pdf');
    Route::get('/offertes/{quote}/edit', \App\Livewire\Verkoper\Quotes\Create::class)->name('quotes.edit');
    Route::get('/offertes/{quote}', \App\Livewire\Verkoper\Quotes\Show::class)->name('quotes.show');
});

Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::get('/', \App\Livewire\Admin\Dashboard\Index::class)->name('dashboard');
    Route::get('/products', \App\Livewire\Admin\Products\Index::class)->name('products.index');
    Route::get('/products/create', \App\Livewire\Admin\Products\Form::class)->name('products.create');
    Route::get('/products/{product}/edit', \App\Livewire\Admin\Products\Form::class)->name('products.edit');
    Route::get('/dependencies', \App\Livewire\Admin\Dependencies\Index::class)->name('dependencies.index');
    Route::get('/users', \App\Livewire\Admin\Users\Index::class)->name('users.index');
    Route::get('/users/create', \App\Livewire\Admin\Users\Form::class)->name('users.create');
    Route::get('/users/{user}/edit', \App\Livewire\Admin\Users\Form::class)->name('users.edit');
    Route::get('/settings', \App\Livewire\Admin\Settings\Index::class)->name('settings.index');
    Route::get('/activity', \App\Livewire\Admin\ActivityLog\Index::class)->name('activity.index');
});

Route::get('/offerte/{token}', \App\Http\Controllers\PublicQuoteController::class)->name('quote.public');

require __DIR__.'/auth.php';
