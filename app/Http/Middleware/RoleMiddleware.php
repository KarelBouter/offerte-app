<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (! $request->user() || ! in_array($request->user()->role, $roles)) {
            return redirect()->route('dashboard')
                ->with('error', 'Je hebt geen toegang tot deze pagina.');
        }

        return $next($request);
    }
}
