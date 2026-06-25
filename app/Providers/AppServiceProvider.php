<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(5)->by($request->ip())
                ->response(function () {
                    return back()->withErrors([
                        'email' => 'Te veel inlogpogingen. Probeer het over 60 seconden opnieuw.',
                    ])->withInput();
                });
        });
    }
}
