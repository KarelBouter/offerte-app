<?php

use Illuminate\Support\Facades\Route;

// Redirect root naar dashboard (detecteert rol) of login
Route::get('/', function () {
    if (!auth()->check()) {
        return redirect()->route('login');
    }
    return redirect()->route('dashboard');
});

// Slimme dashboard redirect op basis van rol
Route::get('/dashboard', function () {
    $role = auth()->user()->role;
    return $role === 'admin'
        ? redirect()->route('beheer.dashboard')
        : redirect()->route('verkoper.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profiel', \App\Livewire\Profile\Edit::class)->name('profile.edit');
});

// ── Verkoper ──────────────────────────────────────────────────────────────────
Route::prefix('verkoper')->name('verkoper.')->middleware(['auth', 'verified', 'role:admin,verkoper'])->group(function () {
    Route::get('/', \App\Livewire\Verkoper\Dashboard::class)->name('dashboard');
    Route::get('/offertes', \App\Livewire\Verkoper\Quotes\Index::class)->name('offertes.index');
    Route::get('/offertes/create', \App\Livewire\Verkoper\Quotes\Create::class)->name('offertes.create');
    Route::get('/offertes/{quote}/pdf', \App\Http\Controllers\QuotePdfController::class)->name('offertes.pdf');
    Route::get('/offertes/{quote}/edit', \App\Livewire\Verkoper\Quotes\Create::class)->name('offertes.edit');
    Route::get('/offertes/{quote}', \App\Livewire\Verkoper\Quotes\Show::class)->name('offertes.show');
});

// ── Beheer (admin) ────────────────────────────────────────────────────────────
Route::prefix('beheer')->name('beheer.')->middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::get('/', \App\Livewire\Admin\Dashboard\Index::class)->name('dashboard');
    Route::get('/producten', \App\Livewire\Admin\Products\Index::class)->name('producten.index');
    Route::get('/producten/create', \App\Livewire\Admin\Products\Form::class)->name('producten.create');
    Route::get('/producten/{product}/edit', \App\Livewire\Admin\Products\Form::class)->name('producten.edit');
    Route::get('/afhankelijkheden', \App\Livewire\Admin\Dependencies\Index::class)->name('afhankelijkheden.index');
    Route::get('/gebruikers', \App\Livewire\Admin\Users\Index::class)->name('gebruikers.index');
    Route::get('/gebruikers/create', \App\Livewire\Admin\Users\Form::class)->name('gebruikers.create');
    Route::get('/gebruikers/{user}/edit', \App\Livewire\Admin\Users\Form::class)->name('gebruikers.edit');
    Route::get('/instellingen', \App\Livewire\Admin\Settings\Index::class)->name('instellingen.index');
    Route::get('/activiteit', \App\Livewire\Admin\ActivityLog\Index::class)->name('activiteit.index');
});

// ── Publieke offerte-link (geen auth vereist) ──────────────────────────────────
Route::get('/offerte/{token}', \App\Http\Controllers\PublicQuoteController::class)->name('quote.public');

require __DIR__.'/auth.php';
