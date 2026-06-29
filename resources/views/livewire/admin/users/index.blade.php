<div>
    <x-breadcrumb :items="[['label' => 'Beheer', 'route' => 'beheer.dashboard'], ['label' => 'Gebruikers']]"/>

    <div class="flex items-center justify-between mb-5">
        <input wire:model.live.debounce.300ms="search" type="text"
               placeholder="Zoek op naam of e-mail…"
               class="w-72 rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"/>
        <a href="{{ route('beheer.gebruikers.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium text-white shadow-sm"
           style="background-color: #1B3A6B;">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Nieuwe gebruiker
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto"><table class="w-full text-sm min-w-[600px]">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200 text-left">
                    <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Naam</th>
                    <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">E-mailadres</th>
                    <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Rol</th>
                    <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Status</th>
                    <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Aangemaakt</th>
                    <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide text-right">Acties</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($users as $user)
                <tr class="hover:bg-gray-50 transition-colors {{ !$user->is_active ? 'opacity-60' : '' }}">
                    <td class="px-5 py-3.5 font-medium text-gray-800">{{ $user->name }}</td>
                    <td class="px-5 py-3.5 text-gray-500">{{ $user->email }}</td>
                    <td class="px-5 py-3.5">
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                            {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700' }}">
                            {{ $user->role === 'admin' ? 'Beheerder' : 'Verkoper' }}
                        </span>
                    </td>
                    <td class="px-5 py-3.5">
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                            {{ $user->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                            {{ $user->is_active ? 'Actief' : 'Inactief' }}
                        </span>
                    </td>
                    <td class="px-5 py-3.5 text-gray-400">{{ $user->created_at->format('d-m-Y') }}</td>
                    <td class="px-5 py-3.5 text-right">
                        <div class="flex items-center justify-end gap-3">
                            <a href="{{ route('beheer.gebruikers.edit', $user) }}"
                               class="text-blue-600 hover:text-blue-800 font-medium">Bewerken</a>

                            @if($user->id !== auth()->id())
                                <button
                                    wire:click="toggleActive({{ $user->id }})"
                                    wire:confirm="{{ $user->is_active ? 'Gebruiker deactiveren?' : 'Gebruiker activeren?' }}"
                                    class="font-medium {{ $user->is_active ? 'text-red-500 hover:text-red-700' : 'text-green-600 hover:text-green-800' }}">
                                    {{ $user->is_active ? 'Deactiveren' : 'Activeren' }}
                                </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-5 py-14 text-center text-gray-400">Geen gebruikers gevonden.</td>
                </tr>
                @endforelse
            </tbody>
        </table></div>
    </div>
</div>
