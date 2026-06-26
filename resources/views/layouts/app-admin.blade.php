<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Beheer' }} — {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('scripts')
</head>
<body class="font-sans antialiased bg-gray-100">

<div class="flex h-screen overflow-hidden">

    {{-- Sidebar --}}
    <aside class="w-64 flex-shrink-0 flex flex-col" style="background-color: #1B3A6B;">
        <div class="px-6 py-5 border-b border-blue-900">
            <p class="text-base font-bold text-white leading-tight">Proud Innovations</p>
            <p class="text-xs text-blue-300 mt-0.5">Offerte Tool — Beheer</p>
        </div>

        <nav class="flex-1 px-3 py-4 space-y-0.5 overflow-y-auto">
            @php
                $conceptCount = \App\Models\Quote::where('status', 'concept')->count();
                $openTakenCount = \App\Models\Task::where('assigned_to_user_id', auth()->id())
                    ->whereIn('status', ['open', 'in_behandeling'])->count();

                $navItems = [
                    ['label' => 'Dashboard',        'route' => 'beheer.dashboard',           'match' => 'beheer.dashboard'],
                    ['label' => 'Offertes',          'route' => 'verkoper.offertes.index',    'match' => 'verkoper.offertes.*', 'badge' => $conceptCount ?: null],
                    ['label' => 'Taken',             'route' => 'taken.index',                'match' => 'taken.*', 'badge' => $openTakenCount ?: null],
                    ['label' => 'Producten',         'route' => 'beheer.producten.index',     'match' => 'beheer.producten.*'],
                    ['label' => 'Afhankelijkheden',  'route' => 'beheer.afhankelijkheden.index', 'match' => 'beheer.afhankelijkheden.*'],
                    ['label' => 'Gebruikers',        'route' => 'beheer.gebruikers.index',    'match' => 'beheer.gebruikers.*'],
                    ['label' => 'Activiteitenlog',   'route' => 'beheer.activiteit.index',    'match' => 'beheer.activiteit.*'],
                    ['label' => 'Instellingen',      'route' => 'beheer.instellingen.index',  'match' => 'beheer.instellingen.*'],
                ];
            @endphp

            @foreach($navItems as $item)
                @php $active = request()->routeIs($item['match']); @endphp
                <a href="{{ route($item['route']) }}"
                   class="flex items-center justify-between px-4 py-2.5 rounded-lg text-sm font-medium transition-colors duration-150
                          {{ $active ? 'bg-white/15 text-white' : 'text-blue-200 hover:bg-white/10 hover:text-white' }}">
                    <span>{{ $item['label'] }}</span>
                    @if(!empty($item['badge']))
                        <span class="ml-2 inline-flex items-center justify-center min-w-[1.25rem] h-5 px-1.5 rounded-full text-xs font-bold
                                     {{ $active ? 'bg-white text-blue-700' : 'bg-blue-500 text-white' }}">
                            {{ $item['badge'] }}
                        </span>
                    @endif
                </a>
            @endforeach

            {{-- Nieuwe taak --}}
            <button onclick="Livewire.dispatch('open-task-modal')"
                    class="w-full flex items-center gap-2 px-4 py-2.5 rounded-lg text-sm font-medium bg-white/10 text-white hover:bg-white/20 transition-colors duration-150 mt-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                Nieuwe taak
            </button>
        </nav>

        <div class="px-3 pt-3 border-t border-blue-900 space-y-0.5">
            <a href="{{ route('profile.edit') }}"
               class="flex items-center px-4 py-2.5 rounded-lg text-sm font-medium transition-colors duration-150
                      {{ request()->routeIs('profile.edit') ? 'bg-white/15 text-white' : 'text-blue-200 hover:bg-white/10 hover:text-white' }}">
                Mijn profiel
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="w-full text-left flex items-center px-4 py-2.5 rounded-lg text-sm font-medium text-blue-200 hover:bg-white/10 hover:text-white transition-colors duration-150">
                    Uitloggen
                </button>
            </form>
        </div>
        <div class="px-6 py-3 border-t border-blue-900">
            <p class="text-xs text-blue-400">&copy; {{ date('Y') }} Proud Innovations B.V.</p>
        </div>
    </aside>

    {{-- Main area --}}
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">

        <header class="bg-white border-b border-gray-200 flex-shrink-0">
            <div class="flex items-center justify-between px-6 h-14">
                <h1 class="text-base font-semibold text-gray-800">{{ $title ?? 'Beheer' }}</h1>
                <div class="flex items-center gap-3">
                    <livewire:notification-bell />
                    <span class="text-sm text-gray-500">{{ Auth::user()->name }}</span>
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto">
            {{-- Breadcrumbs --}}
            @isset($breadcrumbs)
                <div class="px-6 pt-4 pb-0">
                    {{ $breadcrumbs }}
                </div>
            @endisset

            <div class="p-6">
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
