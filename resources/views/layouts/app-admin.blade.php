<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Admin' }} — {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100">

<div class="flex h-screen overflow-hidden">

    {{-- Sidebar --}}
    <aside class="w-64 flex-shrink-0 flex flex-col" style="background-color: #1B3A6B;">
        {{-- Brand --}}
        <div class="px-6 py-5 border-b border-blue-900">
            <p class="text-base font-bold text-white leading-tight">Proud Innovations</p>
            <p class="text-xs text-blue-300 mt-0.5">Offerte Tool</p>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 px-3 py-4 space-y-0.5 overflow-y-auto">
            @php
                $navItems = [
                    ['label' => 'Dashboard',         'route' => 'admin.dashboard',          'match' => 'admin.dashboard'],
                    ['label' => 'Producten',          'route' => 'admin.products.index',   'match' => 'admin.products.*'],
                    ['label' => 'Afhankelijkheden',   'route' => 'admin.dependencies.index', 'match' => 'admin.dependencies.*'],
                    ['label' => 'Gebruikers',         'route' => 'admin.users.index',      'match' => 'admin.users.*'],
                    ['label' => 'Instellingen',       'route' => 'admin.settings.index',   'match' => 'admin.settings.*'],
                ];
            @endphp

            @foreach($navItems as $item)
                @php $active = request()->routeIs($item['match']); @endphp
                <a href="{{ route($item['route']) }}"
                   class="flex items-center px-4 py-2.5 rounded-lg text-sm font-medium transition-colors duration-150
                          {{ $active
                              ? 'bg-white/15 text-white'
                              : 'text-blue-200 hover:bg-white/10 hover:text-white' }}">
                    {{ $item['label'] }}
                </a>
            @endforeach
        </nav>

        {{-- Profile + footer --}}
        <div class="px-3 py-3 border-t border-blue-900">
            <a href="{{ route('profile.edit') }}"
               class="flex items-center px-4 py-2.5 rounded-lg text-sm font-medium transition-colors duration-150
                      {{ request()->routeIs('profile.edit') ? 'bg-white/15 text-white' : 'text-blue-200 hover:bg-white/10 hover:text-white' }}">
                Mijn profiel
            </a>
        </div>
        <div class="px-6 py-3 border-t border-blue-900">
            <p class="text-xs text-blue-400">&copy; {{ date('Y') }} Proud Innovations B.V.</p>
        </div>
    </aside>

    {{-- Main area --}}
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">

        {{-- Topbar --}}
        <header class="bg-white border-b border-gray-200 flex-shrink-0">
            <div class="flex items-center justify-between px-6 h-14">
                <h1 class="text-base font-semibold text-gray-800">{{ $title ?? 'Admin' }}</h1>
                <div class="flex items-center gap-5">
                    <span class="text-sm text-gray-600">{{ Auth::user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="text-sm text-red-500 hover:text-red-700 font-medium transition-colors">
                            Uitloggen
                        </button>
                    </form>
                </div>
            </div>
        </header>

        {{-- Content --}}
        <main class="flex-1 overflow-y-auto p-6">

            {{-- Global flash messages --}}
            @if(session('success'))
                <div class="mb-5 flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 rounded-lg px-4 py-3 text-sm">
                    <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-5 flex items-center gap-3 bg-red-50 border border-red-200 text-red-800 rounded-lg px-4 py-3 text-sm">
                    <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                    {{ session('error') }}
                </div>
            @endif

            {{ $slot }}
        </main>
    </div>
</div>

</body>
</html>
