<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pagina niet gevonden — Proud Innovations</title>
    @vite(['resources/css/app.css'])
</head>
<body class="font-sans antialiased bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="text-center max-w-md px-6">
        <div class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-6"
             style="background-color: #1B3A6B;">
            <span class="text-white font-bold text-2xl">404</span>
        </div>
        <h1 class="text-xl font-semibold text-gray-800 mb-2">Pagina niet gevonden</h1>
        <p class="text-sm text-gray-500 mb-6">
            De pagina die je zoekt bestaat niet of is verplaatst.
        </p>
        @auth
            @php
                $back = auth()->user()->role === 'admin'
                    ? route('beheer.dashboard')
                    : route('verkoper.dashboard');
            @endphp
            <a href="{{ $back }}"
               class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg text-sm font-medium text-white shadow-sm"
               style="background-color: #1B3A6B;">
                ← Terug naar dashboard
            </a>
        @else
            <a href="{{ route('login') }}"
               class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg text-sm font-medium text-white shadow-sm"
               style="background-color: #1B3A6B;">
                ← Naar inlogpagina
            </a>
        @endauth
        <p class="text-xs text-gray-400 mt-8">&copy; {{ date('Y') }} Proud Innovations B.V.</p>
    </div>
</body>
</html>
