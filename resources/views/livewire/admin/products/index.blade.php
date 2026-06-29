<div>
    <x-breadcrumb :items="[['label' => 'Beheer', 'route' => 'beheer.dashboard'], ['label' => 'Producten']]"/>

    {{-- Toolbar --}}
    <div class="flex items-center justify-between mb-5">
        <div class="flex items-center gap-3">
            <input
                wire:model.live.debounce.300ms="search"
                type="text"
                placeholder="Zoeken op naam of categorie…"
                class="w-72 rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
            />
            <select
                wire:model.live="categoryFilter"
                class="rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
            >
                <option value="">Alle categorieën</option>
                @foreach(['Hardware', 'Netwerk', 'Beveiliging', 'Installatie', 'Service'] as $cat)
                    <option value="{{ $cat }}">{{ $cat }}</option>
                @endforeach
            </select>
        </div>
        <a href="{{ route('beheer.producten.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium text-white shadow-sm transition-colors"
           style="background-color: #1B3A6B;">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Nieuw product
        </a>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-visible">
        <div class="overflow-x-auto"><table class="w-full text-sm min-w-[600px]">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200 text-left">
                    <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Naam</th>
                    <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Categorie</th>
                    <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Prijs excl. BTW</th>
                    <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Eenheid</th>
                    <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Status</th>
                    <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide text-right">Acties</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($products as $product)
                    <tr class="{{ $product->is_active ? 'bg-white' : 'bg-gray-50' }} hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="font-medium {{ $product->is_active ? 'text-gray-900' : 'text-gray-400' }}">
                                {{ $product->name }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-gray-500 whitespace-nowrap">{{ $product->category }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($product->is_price_on_quote)
                                <span class="text-amber-600 font-medium">Op offerte</span>
                            @else
                                <span class="{{ $product->is_active ? 'text-gray-700' : 'text-gray-400' }}">
                                    € {{ number_format($product->unit_price, 2, ',', '.') }}
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-gray-500 whitespace-nowrap">{{ $product->unit }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($product->is_active)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                    Actief
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-200 text-gray-500">
                                    Gearchiveerd
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right whitespace-nowrap">
                            <div class="flex items-center justify-end gap-4">
                                <a href="{{ route('beheer.producten.edit', $product) }}"
                                   class="text-blue-600 hover:text-blue-800 font-medium transition-colors">
                                    Bewerken
                                </a>
                                <button
                                    wire:click="toggleActive({{ $product->id }})"
                                    wire:confirm="{{ $product->is_active ? 'Product archiveren?' : 'Product activeren?' }}"
                                    class="{{ $product->is_active ? 'text-red-500 hover:text-red-700' : 'text-green-600 hover:text-green-800' }} font-medium transition-colors"
                                >
                                    {{ $product->is_active ? 'Archiveren' : 'Activeren' }}
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center text-gray-400">
                            <svg class="w-8 h-8 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                            </svg>
                            Geen producten gevonden.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table></div>
    </div>
</div>
