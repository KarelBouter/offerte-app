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
    return match($role) {
        'admin'             => redirect()->route('beheer.dashboard'),
        'werkvoorbereider'  => redirect()->route('werkbon.index'),
        default             => redirect()->route('verkoper.dashboard'),
    };
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profiel', \App\Livewire\Profile\Edit::class)->name('profile.edit');
});

// ── Gedeelde routes (admin + verkoper + samensteller) ─────────────────────────
Route::middleware(['auth', 'verified', 'role:admin,verkoper,samensteller'])->group(function () {
    Route::get('/taken', \App\Livewire\Tasks\Index::class)->name('taken.index');
    Route::get('/taken/{task}', \App\Livewire\Tasks\Show::class)->name('taken.show');
    Route::get('/notificaties', \App\Livewire\Notifications\Index::class)->name('notificaties.index');
});

// ── Werkbon (admin + verkoper + werkvoorbereider) ─────────────────────────────
Route::middleware(['auth', 'verified', 'role:admin,verkoper,werkvoorbereider'])->group(function () {
    Route::get('/werkbon', \App\Livewire\Werkbon\Index::class)->name('werkbon.index');
    Route::get('/werkbon/{quote}/bewerken', \App\Livewire\Werkbon\Edit::class)->name('werkbon.edit');
});

// ── Verkoper ──────────────────────────────────────────────────────────────────
Route::prefix('verkoper')->name('verkoper.')->group(function () {
    // Samensteller mag ook deze routes
    Route::middleware(['auth', 'verified', 'role:admin,verkoper,samensteller'])->group(function () {
        Route::get('/', \App\Livewire\Verkoper\Dashboard::class)->name('dashboard');
        Route::get('/offertes', \App\Livewire\Verkoper\Quotes\Index::class)->name('offertes.index');
        Route::get('/offertes/create', \App\Livewire\Verkoper\Quotes\Create::class)->name('offertes.create');
        Route::get('/offertes/{quote}/edit', \App\Livewire\Verkoper\Quotes\Create::class)->name('offertes.edit');
        Route::get('/offertes/{quote}', \App\Livewire\Verkoper\Quotes\Show::class)->name('offertes.show');
        Route::get('/klanten', \App\Livewire\Verkoper\Customers\Index::class)->name('klanten.index');
        Route::get('/klanten/{customer}', \App\Livewire\Verkoper\Customers\Show::class)->name('klanten.show');
    });

    // PDF-download en werkbon — samensteller mag dit niet
    Route::middleware(['auth', 'verified', 'role:admin,verkoper'])->group(function () {
        Route::get('/offertes/{quote}/pdf', \App\Http\Controllers\QuotePdfController::class)->name('offertes.pdf');
        Route::get('/offertes/{quote}/werkbon', \App\Http\Controllers\WerkbonPdfController::class)->name('offertes.werkbon');
    });
});

// ── Beheer (admin) ────────────────────────────────────────────────────────────
Route::prefix('beheer')->name('beheer.')->middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::get('/', \App\Livewire\Admin\Dashboard\Index::class)->name('dashboard');
    Route::get('/producten', \App\Livewire\Admin\Products\Index::class)->name('producten.index');
    Route::get('/producten/create', \App\Livewire\Admin\Products\Form::class)->name('producten.create');
    Route::get('/producten/{product}/edit', \App\Livewire\Admin\Products\Form::class)->name('producten.edit');
    Route::get('/afhankelijkheden', \App\Livewire\Admin\Dependencies\Index::class)->name('afhankelijkheden.index');
    Route::get('/kassa-componenten', \App\Livewire\Admin\KassaComponenten\Index::class)->name('kassa-componenten.index');
    Route::get('/gebruikers', \App\Livewire\Admin\Users\Index::class)->name('gebruikers.index');
    Route::get('/gebruikers/create', \App\Livewire\Admin\Users\Form::class)->name('gebruikers.create');
    Route::get('/gebruikers/{user}/edit', \App\Livewire\Admin\Users\Form::class)->name('gebruikers.edit');
    Route::get('/instellingen', \App\Livewire\Admin\Settings\Index::class)->name('instellingen.index');
    Route::get('/activiteit', \App\Livewire\Admin\ActivityLog\Index::class)->name('activiteit.index');
});

// ── Publieke offerte-link (geen auth vereist) ──────────────────────────────────
Route::middleware('throttle:30,1')->group(function () {
    Route::get('/offerte/{token}', \App\Http\Controllers\PublicQuoteController::class)->name('quote.public');
    Route::post('/offerte/{token}/ondertekenen', \App\Http\Controllers\PublicQuoteSignController::class)->name('quote.sign');
    Route::get('/offerte/{token}/bedankt', function (string $token) {
        $quote = \App\Models\Quote::where('sign_token', $token)->with('customer')->firstOrFail();
        return view('public.quote-signed', compact('quote'));
    })->name('quote.signed');
});

require __DIR__.'/auth.php';
