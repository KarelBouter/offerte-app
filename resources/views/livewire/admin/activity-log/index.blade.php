<div>
    <x-breadcrumb :items="[['label' => 'Beheer', 'route' => 'beheer.dashboard'], ['label' => 'Activiteitenlog']]"/>

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <h2 class="text-lg font-semibold text-gray-800">Activiteitenlog</h2>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-xl border border-gray-200 p-4 mb-5 flex flex-col sm:flex-row gap-3">
        <select wire:model.live="userFilter"
                class="border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">Alle gebruikers</option>
            @foreach($users as $user)
                <option value="{{ $user->id }}">{{ $user->name }}</option>
            @endforeach
        </select>

        <select wire:model.live="actionFilter"
                class="border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">Alle acties</option>
            @foreach($actionPrefixes as $prefix => $label)
                <option value="{{ $prefix }}">{{ $label }}</option>
            @endforeach
        </select>

        @if($userFilter || $actionFilter)
            <button wire:click="$set('userFilter','');$set('actionFilter','')"
                    class="text-sm text-gray-500 hover:text-gray-700 underline">
                Wis filters
            </button>
        @endif
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-visible">
        <div class="overflow-x-auto"><table class="w-full text-sm text-left min-w-[600px]">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Datum & tijd</th>
                    <th class="px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Gebruiker</th>
                    <th class="px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Actie</th>
                    <th class="px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Omschrijving</th>
                    <th class="px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">IP-adres</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($logs as $log)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-gray-600 whitespace-nowrap">
                            {{ $log->created_at->format('d-m-Y H:i:s') }}
                        </td>
                        <td class="px-4 py-3 text-gray-800">
                            {{ $log->user?->name ?? '—' }}
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                {{ str_starts_with($log->action, 'quote')   ? 'bg-blue-100 text-blue-700'   : '' }}
                                {{ str_starts_with($log->action, 'product') ? 'bg-purple-100 text-purple-700' : '' }}
                                {{ str_starts_with($log->action, 'user')    ? 'bg-green-100 text-green-700'  : '' }}
                                {{ !in_array(explode('.', $log->action)[0], ['quote','product','user']) ? 'bg-gray-100 text-gray-600' : '' }}
                            ">
                                {{ $log->action }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-gray-700">{{ $log->description }}</td>
                        <td class="px-4 py-3 text-gray-500 font-mono text-xs">{{ $log->ip_address ?? '—' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-10 text-center text-gray-400 text-sm">
                            Geen activiteiten gevonden.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table></div>
    </div>

    @if($logs->count() === 200)
        <p class="mt-3 text-xs text-gray-400 text-center">Maximaal 200 regels getoond. Gebruik filters om te verfijnen.</p>
    @endif
</div>
