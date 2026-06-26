<div class="relative" x-data="{ open: @entangle('open') }" x-on:click.outside="open = false">

    {{-- Bel-knop --}}
    <button wire:click="toggle"
            class="relative flex items-center justify-center w-9 h-9 rounded-lg text-gray-500 hover:bg-gray-100 hover:text-gray-700 transition-colors">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
        </svg>
        @if($unreadCount > 0)
            <span class="absolute -top-0.5 -right-0.5 flex items-center justify-center min-w-[1.1rem] h-4 px-1 rounded-full text-xs font-bold bg-red-500 text-white leading-none">
                {{ $unreadCount > 9 ? '9+' : $unreadCount }}
            </span>
        @endif
    </button>

    {{-- Dropdown --}}
    <div x-show="open" x-transition
         class="absolute right-0 top-full mt-2 w-96 bg-white border border-gray-200 rounded-xl shadow-xl z-50 overflow-hidden">

        <div class="px-4 py-3 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-sm font-semibold text-gray-800">Notificaties</h3>
            @if($unreadCount > 0)
                <button wire:click="markAllRead" class="text-xs text-blue-600 hover:text-blue-800">
                    Alles als gelezen markeren
                </button>
            @endif
        </div>

        <div class="divide-y divide-gray-50 max-h-96 overflow-y-auto">
            @forelse($notifications as $n)
                <button wire:click="markRead({{ $n->id }})"
                        class="w-full text-left px-4 py-3 hover:bg-gray-50 transition-colors
                               {{ is_null($n->read_at) ? 'bg-blue-50' : '' }}">
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 mt-0.5">
                            <div class="w-2 h-2 rounded-full mt-1 {{ is_null($n->read_at) ? 'bg-blue-500' : 'bg-transparent' }}"></div>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-xs font-semibold text-gray-800 leading-snug">{{ $n->title }}</p>
                            <p class="text-xs text-gray-500 mt-0.5 leading-snug">{{ $n->body }}</p>
                            <p class="text-xs text-gray-400 mt-1">{{ $n->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                </button>
            @empty
                <div class="px-4 py-8 text-center text-sm text-gray-400">
                    Geen notificaties
                </div>
            @endforelse
        </div>

        <div class="px-4 py-2.5 border-t border-gray-100 bg-gray-50">
            <a href="{{ route('notificaties.index') }}"
               class="text-xs text-blue-600 hover:text-blue-800 font-medium">
                Alle notificaties bekijken →
            </a>
        </div>
    </div>
</div>
