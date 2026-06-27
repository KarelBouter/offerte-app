<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Offerte' }} — Proud Innovations B.V.</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50">

    <header class="bg-white border-b border-gray-200">
        <div class="max-w-4xl mx-auto px-6 py-4 flex items-center justify-between">
            <div>
                <p class="text-base font-bold text-gray-900 leading-tight">Proud Innovations B.V.</p>
                <p class="text-xs text-gray-500">Kassa Continuïteitsdienst — Offerte</p>
            </div>
            <div class="text-right text-xs text-gray-400">
                <p>info@proudinnovations.nl</p>
                <p>www.proudinnovations.nl</p>
            </div>
        </div>
    </header>

    <main class="max-w-4xl mx-auto px-6 py-10">
        {{ $slot }}
    </main>

    <footer class="border-t border-gray-200 mt-10">
        <div class="max-w-4xl mx-auto px-6 py-5 text-center text-xs text-gray-400">
            &copy; {{ date('Y') }} Proud Innovations B.V. — KvK: 12345678 — BTW: NL123456789B01
        </div>
    </footer>

</body>
</html>
