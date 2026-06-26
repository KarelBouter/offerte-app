<div>
    <x-breadcrumb :items="[['label' => 'Notificaties']]"/>

    <div class="flex items-center justify-between mb-5">
        <h2 class="text-lg font-semibold text-gray-800">Notificaties</h2>
        <button wire:click="markAllRead"
                class="text-sm text-blue-600 hover:text-blue-800 font-medium">
            Alles als gelezen markeren
        </button>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200 text-left">
                    <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Tijdstip</th>
                    <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Titel</th>
                    <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Omschrijving</th>
                    <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Gelezen</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($notifications as $n)
                    <tr wire:click="markRead({{ $n->id }})"
                        class="cursor-pointer hover:bg-gray-50 transition-colors {{ is_null($n->read_at) ? 'bg-blue-50' : '' }}">
                        <td class="px-5 py-3.5 text-gray-400 whitespace-nowrap">
                            {{ $n->created_at->diffForHumans() }}
                        </td>
                        <td class="px-5 py-3.5 font-medium text-gray-800">{{ $n->title }}</td>
                        <td class="px-5 py-3.5 text-gray-600">{{ $n->body }}</td>
                        <td class="px-5 py-3.5">
                            @if($n->read_at)
                                <span class="text-xs text-gray-400">{{ $n->read_at->format('d-m-Y H:i') }}</span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                                    Ongelezen
                                </span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-5 py-14 text-center text-gray-400">
                            Geen notificaties.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
