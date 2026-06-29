<div>
    <x-breadcrumb :items="[['label' => 'Offertes']]"/>

    {{-- Toolbar --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
        <div class="flex flex-wrap items-center gap-3">
            <input wire:model.live.debounce.300ms="search" type="text"
                   placeholder="Zoek op offertenummer of klantnaam…"
                   class="w-72 rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"/>
            <select wire:model.live="statusFilter"
                    class="rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <option value="">Alle statussen</option>
                @foreach($statusLabels as $value => $label)
                    <option value="{{ $value }}">{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <a href="{{ route('verkoper.offertes.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium text-white shadow-sm flex-shrink-0"
           style="background-color: #1B3A6B;">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Nieuwe offerte
        </a>
    </div>

    {{-- Actieve URL-filter badge --}}
    @if($urlFilter)
        <div class="flex items-center gap-2 mb-4">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                Filter: {{ \App\Livewire\Verkoper\Quotes\Index::URL_FILTER_LABELS[$urlFilter] ?? $urlFilter }}
                <button wire:click="clearFilter" class="ml-1 hover:text-blue-900">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </span>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-visible">
    <table class="w-full text-sm">
        <thead>
            <tr class="bg-gray-50 border-b border-gray-200 text-left">
                <th class="px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Nummer</th>
                <th class="px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Klant</th>
                <th class="px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide hidden sm:table-cell">Verkoper</th>
                <th class="px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Status</th>
                <th class="px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide hidden sm:table-cell">Datum</th>
                <th class="px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide hidden lg:table-cell">Geldig tot</th>
                <th class="px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide text-right hidden lg:table-cell">Totaal eenmalig</th>
                <th class="px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide text-right hidden lg:table-cell">Per jaar</th>
                <th class="px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide text-right">Acties</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($quotes as $quote)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-4 py-3.5 font-mono text-xs font-medium text-gray-800 whitespace-nowrap">{{ $quote->quote_number }}</td>
                    <td class="px-4 py-3.5 font-medium text-gray-800 whitespace-nowrap">{{ $quote->customer?->company_name ?? '—' }}</td>
                    <td class="px-4 py-3.5 text-gray-500 whitespace-nowrap hidden sm:table-cell">{{ $quote->user?->name ?? '—' }}</td>
                    <td class="px-4 py-3.5 whitespace-nowrap">
                        @php
                            $colors = ['concept'=>'bg-gray-100 text-gray-600','verzonden'=>'bg-blue-100 text-blue-700','ondertekend'=>'bg-green-100 text-green-700','verlopen'=>'bg-orange-100 text-orange-700','geannuleerd'=>'bg-red-100 text-red-700'];
                        @endphp
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $colors[$quote->status] ?? 'bg-gray-100 text-gray-600' }}">
                            {{ $statusLabels[$quote->status] ?? $quote->status }}
                        </span>
                    </td>
                    <td class="px-4 py-3.5 text-gray-500 whitespace-nowrap hidden sm:table-cell">{{ $quote->created_at->format('d-m-Y') }}</td>
                    <td class="px-4 py-3.5 text-gray-500 whitespace-nowrap hidden lg:table-cell">{{ $quote->valid_until ? \Carbon\Carbon::parse($quote->valid_until)->format('d-m-Y') : '—' }}</td>
                    <td class="px-4 py-3.5 text-right text-gray-700 whitespace-nowrap hidden lg:table-cell">€ {{ number_format($quote->total_onetime_excl_vat, 2, ',', '.') }}</td>
                    <td class="px-4 py-3.5 text-right text-gray-700 whitespace-nowrap hidden lg:table-cell">€ {{ number_format($quote->total_yearly_excl_vat, 2, ',', '.') }}</td>
                    <td class="px-4 py-3.5 text-right whitespace-nowrap">
                        <div class="flex items-center justify-end gap-3">
                            <a href="{{ route('verkoper.offertes.show', $quote) }}" class="text-blue-600 hover:text-blue-800 font-medium">Bekijken</a>
                            @if($quote->status === 'concept')
                                <a href="{{ route('verkoper.offertes.edit', $quote) }}" class="text-gray-500 hover:text-gray-700 font-medium">Bewerken</a>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="px-5 py-16 text-center">
                        <svg class="w-10 h-10 mx-auto mb-3 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        @if($search || $statusFilter || $urlFilter)
                            <p class="text-sm text-gray-400">Geen offertes gevonden met de huidige filters.</p>
                        @else
                            <p class="text-sm font-medium text-gray-500 mb-3">Nog geen offertes aangemaakt</p>
                            <a href="{{ route('verkoper.offertes.create') }}"
                               class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium text-white"
                               style="background-color: #1B3A6B;">
                                Maak je eerste offerte aan
                            </a>
                        @endif
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    </div>
</div>
