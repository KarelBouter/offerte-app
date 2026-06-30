<div x-on:livewire-navigate-end.window="$wire.$refresh()">
    <x-breadcrumb :items="[['label' => 'Kassa-componenten']]"/>

    {{-- Flash --}}
    @if(session('success'))
    <div class="mb-4 bg-green-50 border border-green-200 text-green-800 rounded-lg px-4 py-3 text-sm">
        {{ session('success') }}
    </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <div>
                <h2 class="text-base font-semibold text-gray-800">Kassa-componenten</h2>
                <p class="text-xs text-gray-400 mt-0.5">
                    Bepaal welke netwerkpoorten per kassa worden meegerekend in de switch-selectie.
                    Actieve componenten tellen mee in de offerte-bouwer.
                </p>
            </div>
            <button wire:click="openCreate"
                    class="flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium text-white"
                    style="background-color: #1B3A6B;">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                Component toevoegen
            </button>
        </div>

        @if($componenten->isEmpty())
        <div class="px-5 py-10 text-center text-sm text-gray-400">
            Nog geen kassa-componenten. Voeg er een toe om te beginnen.
        </div>
        @else
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100 text-left bg-gray-50">
                    <th class="px-5 py-3 text-xs font-semibold text-gray-500">Volgorde</th>
                    <th class="px-5 py-3 text-xs font-semibold text-gray-500">Naam</th>
                    <th class="px-5 py-3 text-xs font-semibold text-gray-500 text-center">Poorten / kassa</th>
                    <th class="px-5 py-3 text-xs font-semibold text-gray-500 text-center">PoE vereist</th>
                    <th class="px-5 py-3 text-xs font-semibold text-gray-500 text-center">Actief</th>
                    <th class="px-5 py-3 text-xs font-semibold text-gray-500 text-right">Acties</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($componenten as $c)
                <tr class="{{ $c->is_actief ? '' : 'opacity-50' }}">
                    <td class="px-5 py-3 text-gray-400">{{ $c->sort_order }}</td>
                    <td class="px-5 py-3 font-medium text-gray-800">{{ $c->naam }}</td>
                    <td class="px-5 py-3 text-center text-gray-700">{{ $c->poorten_per_kassa }}</td>
                    <td class="px-5 py-3 text-center">
                        @if($c->poe_required)
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-orange-100 text-orange-700">PoE</span>
                        @else
                            <span class="text-gray-300">—</span>
                        @endif
                    </td>
                    <td class="px-5 py-3 text-center">
                        <button wire:click="toggleActief({{ $c->id }})"
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                       {{ $c->is_actief ? 'bg-green-100 text-green-700 hover:bg-green-200' : 'bg-gray-100 text-gray-500 hover:bg-gray-200' }}">
                            {{ $c->is_actief ? 'Actief' : 'Inactief' }}
                        </button>
                    </td>
                    <td class="px-5 py-3 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <button wire:click="openEdit({{ $c->id }})"
                                    class="text-xs text-blue-600 hover:text-blue-800 font-medium">
                                Bewerken
                            </button>
                            <button wire:click="prepareConfirmDelete({{ $c->id }}, '{{ addslashes($c->naam) }}')"
                                    class="text-xs text-red-500 hover:text-red-700 font-medium">
                                Verwijderen
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot class="border-t border-gray-200 bg-gray-50">
                <tr>
                    <td colspan="6" class="px-5 py-2.5 text-xs text-gray-500">
                        @php $totaalPoorten = $componenten->where('is_actief', true)->sum('poorten_per_kassa'); @endphp
                        Actieve componenten: {{ $totaalPoorten }} poort(en) per kassa + 1 uplink
                        = bij 3 kassa's bijv. {{ 1 + (3 * $totaalPoorten) }} poorten benodigd.
                    </td>
                </tr>
            </tfoot>
        </table>
        @endif
    </div>

    {{-- Modal --}}
    @if($showModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40">
        <div class="bg-white rounded-xl shadow-xl p-6 max-w-md w-full mx-4">
            <h3 class="text-base font-semibold text-gray-800 mb-4">
                {{ $editingId ? 'Component bewerken' : 'Component toevoegen' }}
            </h3>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Naam <span class="text-red-500">*</span></label>
                    <input wire:model="naam" type="text"
                           class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
                           placeholder="bijv. Kassa, Pinautomaat, Scanner"/>
                    @error('naam') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Netwerkpoorten per kassa <span class="text-red-500">*</span></label>
                    <input wire:model="poorten_per_kassa" type="number" min="0"
                           class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"/>
                    <p class="mt-1 text-xs text-gray-400">Aantal poorten dat dit component per kassa-locatie inneemt op de switch.</p>
                    @error('poorten_per_kassa') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Volgorde</label>
                    <input wire:model="sort_order" type="number" min="0"
                           class="w-32 rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"/>
                </div>

                <div class="flex flex-col gap-3">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input wire:model="poe_required" type="checkbox"
                               class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"/>
                        <span class="text-sm font-medium text-gray-700">PoE vereist</span>
                        <span class="text-xs text-gray-400">(zorgt dat er een PoE-switch wordt geselecteerd)</span>
                    </label>

                    <label class="flex items-center gap-3 cursor-pointer">
                        <input wire:model="is_actief" type="checkbox"
                               class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"/>
                        <span class="text-sm font-medium text-gray-700">Actief</span>
                        <span class="text-xs text-gray-400">(inactieve componenten worden niet meegerekend)</span>
                    </label>
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-6">
                <button wire:click="closeModal"
                        class="px-4 py-2 text-sm text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                    Annuleren
                </button>
                <button wire:click="save"
                        class="px-4 py-2 text-sm font-medium text-white rounded-lg"
                        style="background-color: #1B3A6B;">
                    {{ $editingId ? 'Opslaan' : 'Toevoegen' }}
                </button>
            </div>
        </div>
    </div>
    @endif

<x-confirm-modal name="confirm-kassa-component"
    title="Kassa-component verwijderen?"
    :message="'Weet je zeker dat je \'' . $confirmingName . '\' wilt verwijderen?'"
    variant="danger">
    <button wire:click="delete({{ $confirmingId ?? 0 }})"
            class="px-4 py-2 rounded-lg text-sm font-medium text-white bg-red-600 hover:bg-red-700 transition-colors">
        Verwijderen
    </button>
</x-confirm-modal>
</div>
