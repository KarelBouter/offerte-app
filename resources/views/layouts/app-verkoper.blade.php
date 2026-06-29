<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Offertes' }} — {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('scripts')
    <style>
[x-cloak]{display:none!important}
@media (max-width: 1180px) { #desktop-sidebar { display: none !important; visibility: hidden !important; opacity: 0 !important; width: 0 !important; overflow: hidden !important; } }
@media (min-width: 1181px) { #hamburger-btn { display: none !important; } }
</style>
</head>
<body class="font-sans antialiased bg-gray-100">

<div class="flex h-screen overflow-hidden" x-data="{ open: false }">

    {{-- Desktop sidebar: gewoon in de flow, verborgen onder lg --}}
    <aside class="flex-shrink-0 flex flex-col w-64" id="desktop-sidebar" style="background-color: #1B3A6B;">
        <div class="px-6 py-5 border-b border-blue-900">
            <p class="text-base font-bold text-white leading-tight">Proud Innovations</p>
            <p class="text-xs text-blue-300 mt-0.5">Offerte Tool</p>
        </div>
        <nav class="flex-1 px-3 py-4 space-y-0.5 overflow-y-auto">
            @php
                $conceptCount   = \App\Models\Quote::where('status', 'concept')->count();
                $openTakenCount = \App\Models\Task::where('assigned_to_user_id', auth()->id())
                    ->whereIn('status', ['open', 'in_behandeling'])->count();
            @endphp
            <a href="{{ route('verkoper.dashboard') }}" wire:navigate
               class="flex items-center px-4 py-2.5 rounded-lg text-sm font-medium transition-colors duration-150
                      {{ request()->routeIs('verkoper.dashboard') ? 'bg-white/15 text-white' : 'text-blue-200 hover:bg-white/10 hover:text-white' }}">
                Dashboard
            </a>
            <a href="{{ route('verkoper.offertes.index') }}" wire:navigate
               class="flex items-center justify-between px-4 py-2.5 rounded-lg text-sm font-medium transition-colors duration-150
                      {{ request()->routeIs('verkoper.offertes.*') && !request()->routeIs('verkoper.offertes.create') ? 'bg-white/15 text-white' : 'text-blue-200 hover:bg-white/10 hover:text-white' }}">
                <span>Offertes</span>
                @if($conceptCount > 0)
                    <span class="ml-2 inline-flex items-center justify-center min-w-[1.25rem] h-5 px-1.5 rounded-full text-xs font-bold
                                 {{ request()->routeIs('verkoper.offertes.*') && !request()->routeIs('verkoper.offertes.create') ? 'bg-white text-blue-700' : 'bg-blue-500 text-white' }}">
                        {{ $conceptCount }}
                    </span>
                @endif
            </a>
            <a href="{{ route('taken.index') }}" wire:navigate
               class="flex items-center justify-between px-4 py-2.5 rounded-lg text-sm font-medium transition-colors duration-150
                      {{ request()->routeIs('taken.*') ? 'bg-white/15 text-white' : 'text-blue-200 hover:bg-white/10 hover:text-white' }}">
                <span>Taken</span>
                @if($openTakenCount > 0)
                    <span class="ml-2 inline-flex items-center justify-center min-w-[1.25rem] h-5 px-1.5 rounded-full text-xs font-bold
                                 {{ request()->routeIs('taken.*') ? 'bg-white text-blue-700' : 'bg-blue-500 text-white' }}">
                        {{ $openTakenCount }}
                    </span>
                @endif
            </a>
            <a href="{{ route('verkoper.klanten.index') }}" wire:navigate
               class="flex items-center px-4 py-2.5 rounded-lg text-sm font-medium transition-colors duration-150
                      {{ request()->routeIs('verkoper.klanten.*') ? 'bg-white/15 text-white' : 'text-blue-200 hover:bg-white/10 hover:text-white' }}">
                Klanten
            </a>
        </nav>
        <div class="px-3 pb-2 space-y-1">
            <a href="{{ route('verkoper.offertes.create') }}" wire:navigate
               class="flex items-center gap-2 px-4 py-2.5 rounded-lg text-sm font-medium transition-colors duration-150
                      {{ request()->routeIs('verkoper.offertes.create') ? 'bg-white text-blue-800' : 'bg-white/10 text-white hover:bg-white/20' }}">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                Nieuwe offerte
            </a>
            <button onclick="Livewire.dispatch('open-task-modal')"
                    class="w-full flex items-center gap-2 px-4 py-2.5 rounded-lg text-sm font-medium bg-white/10 text-white hover:bg-white/20 transition-colors duration-150">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                Nieuwe taak
            </button>
        </div>
        <div class="px-3 pt-3 border-t border-blue-900 space-y-0.5">
            <a href="{{ route('profile.edit') }}" wire:navigate
               class="flex items-center px-4 py-2.5 rounded-lg text-sm font-medium transition-colors duration-150
                      {{ request()->routeIs('profile.edit') ? 'bg-white/15 text-white' : 'text-blue-200 hover:bg-white/10 hover:text-white' }}">
                Mijn profiel
            </a>
            @if(Auth::user()->role === 'admin')
                <a href="{{ route('beheer.dashboard') }}" wire:navigate
                   class="flex items-center px-4 py-2.5 rounded-lg text-sm font-medium text-blue-200 hover:bg-white/10 hover:text-white transition-colors duration-150">
                    Beheer ↗
                </a>
            @endif
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full text-left flex items-center px-4 py-2.5 rounded-lg text-sm font-medium text-blue-200 hover:bg-white/10 hover:text-white transition-colors duration-150">
                    Uitloggen
                </button>
            </form>
        </div>
        <div class="px-6 py-3 border-t border-blue-900">
            <p class="text-xs text-blue-400">&copy; {{ date('Y') }} Proud Innovations B.V.</p>
        </div>
    </aside>

    {{-- Mobiele sidebar: fixed overlay, alleen op < lg --}}
    <div x-cloak x-show="open" class="fixed inset-0 z-40 flex lg:hidden">
        {{-- Sidebar --}}
        <div class="relative z-10 flex flex-col w-64 flex-shrink-0" style="background-color: #1B3A6B;">
            <div class="flex items-center justify-between px-6 py-5 border-b border-blue-900">
                <div>
                    <p class="text-base font-bold text-white leading-tight">Proud Innovations</p>
                    <p class="text-xs text-blue-300 mt-0.5">Offerte Tool</p>
                </div>
                <button @click="open = false" class="p-1 text-blue-300 hover:text-white">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <nav class="flex-1 px-3 py-4 space-y-0.5 overflow-y-auto">
                @php
                    $conceptCount   = \App\Models\Quote::where('status', 'concept')->count();
                    $openTakenCount = \App\Models\Task::where('assigned_to_user_id', auth()->id())
                        ->whereIn('status', ['open', 'in_behandeling'])->count();
                @endphp
                <a href="{{ route('verkoper.dashboard') }}" wire:navigate @click="open = false"
                   class="flex items-center px-4 py-2.5 rounded-lg text-sm font-medium transition-colors duration-150
                          {{ request()->routeIs('verkoper.dashboard') ? 'bg-white/15 text-white' : 'text-blue-200 hover:bg-white/10 hover:text-white' }}">
                    Dashboard
                </a>
                <a href="{{ route('verkoper.offertes.index') }}" wire:navigate @click="open = false"
                   class="flex items-center justify-between px-4 py-2.5 rounded-lg text-sm font-medium transition-colors duration-150
                          {{ request()->routeIs('verkoper.offertes.*') && !request()->routeIs('verkoper.offertes.create') ? 'bg-white/15 text-white' : 'text-blue-200 hover:bg-white/10 hover:text-white' }}">
                    <span>Offertes</span>
                    @if($conceptCount > 0)
                        <span class="ml-2 inline-flex items-center justify-center min-w-[1.25rem] h-5 px-1.5 rounded-full text-xs font-bold bg-blue-500 text-white">{{ $conceptCount }}</span>
                    @endif
                </a>
                <a href="{{ route('taken.index') }}" wire:navigate @click="open = false"
                   class="flex items-center justify-between px-4 py-2.5 rounded-lg text-sm font-medium transition-colors duration-150
                          {{ request()->routeIs('taken.*') ? 'bg-white/15 text-white' : 'text-blue-200 hover:bg-white/10 hover:text-white' }}">
                    <span>Taken</span>
                    @if($openTakenCount > 0)
                        <span class="ml-2 inline-flex items-center justify-center min-w-[1.25rem] h-5 px-1.5 rounded-full text-xs font-bold bg-blue-500 text-white">{{ $openTakenCount }}</span>
                    @endif
                </a>
                <a href="{{ route('verkoper.klanten.index') }}" wire:navigate @click="open = false"
                   class="flex items-center px-4 py-2.5 rounded-lg text-sm font-medium transition-colors duration-150
                          {{ request()->routeIs('verkoper.klanten.*') ? 'bg-white/15 text-white' : 'text-blue-200 hover:bg-white/10 hover:text-white' }}">
                    Klanten
                </a>
            </nav>
            <div class="px-3 pb-2 space-y-1">
                <a href="{{ route('verkoper.offertes.create') }}" wire:navigate @click="open = false"
                   class="flex items-center gap-2 px-4 py-2.5 rounded-lg text-sm font-medium bg-white/10 text-white hover:bg-white/20 transition-colors duration-150">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    Nieuwe offerte
                </a>
                <button onclick="Livewire.dispatch('open-task-modal')"
                        class="w-full flex items-center gap-2 px-4 py-2.5 rounded-lg text-sm font-medium bg-white/10 text-white hover:bg-white/20 transition-colors duration-150">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    Nieuwe taak
                </button>
            </div>
            <div class="px-3 pt-3 border-t border-blue-900 space-y-0.5">
                <a href="{{ route('profile.edit') }}" wire:navigate @click="open = false"
                   class="flex items-center px-4 py-2.5 rounded-lg text-sm font-medium transition-colors duration-150
                          {{ request()->routeIs('profile.edit') ? 'bg-white/15 text-white' : 'text-blue-200 hover:bg-white/10 hover:text-white' }}">
                    Mijn profiel
                </a>
                @if(Auth::user()->role === 'admin')
                    <a href="{{ route('beheer.dashboard') }}" wire:navigate @click="open = false"
                       class="flex items-center px-4 py-2.5 rounded-lg text-sm font-medium text-blue-200 hover:bg-white/10 hover:text-white transition-colors duration-150">
                        Beheer ↗
                    </a>
                @endif
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left flex items-center px-4 py-2.5 rounded-lg text-sm font-medium text-blue-200 hover:bg-white/10 hover:text-white transition-colors duration-150">
                        Uitloggen
                    </button>
                </form>
            </div>
            <div class="px-6 py-3 border-t border-blue-900">
                <p class="text-xs text-blue-400">&copy; {{ date('Y') }} Proud Innovations B.V.</p>
            </div>
        </div>
        {{-- Donkere achtergrond rechts van de sidebar — klik sluit het menu --}}
        <div class="flex-1 bg-black/50" @click="open = false"></div>
    </div>

    {{-- Main --}}
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
        <header class="bg-white border-b border-gray-200 flex-shrink-0">
            <div class="flex items-center justify-between px-4 lg:px-6 h-14">
                <div class="flex items-center gap-3">
                    <button @click="open = true"
                            class="lg:hidden p-2 rounded-lg text-gray-500 hover:bg-gray-100 hover:text-gray-700 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                    <h1 class="text-base font-semibold text-gray-800">{{ $title ?? 'Offertes' }}</h1>
                </div>
                <div class="flex items-center gap-3">
                    <livewire:notification-bell />
                    <span class="text-sm text-gray-500 hidden sm:inline">{{ Auth::user()->name }}</span>
                </div>
            </div>
        </header>
        <main class="flex-1 overflow-y-auto">
            @isset($breadcrumbs)
                <div class="px-4 lg:px-6 pt-4 pb-0">{{ $breadcrumbs }}</div>
            @endisset
            <div class="p-4 lg:p-6">
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
            </div>
        </main>
    </div>
</div>

<livewire:tasks.modal />
</body>
</html>
