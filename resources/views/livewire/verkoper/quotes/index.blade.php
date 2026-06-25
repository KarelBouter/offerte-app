<div>
    {{-- Toolbar --}}
    <div class="flex items-center justify-between mb-5">
        <div class="flex items-center gap-3">
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
        <a href="{{ route('verkoper.quotes.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium text-white shadow-sm"
           style="background-color: #1B3A6B;">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Nieuwe offerte
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200 text-left">
                    <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Nummer</th>
                    <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Klant</th>
                    <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Verkoper</th>
                    <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Status</th>
                    <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Datum</th>
                    <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Geldig tot</th>
                    <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide text-right">Totaal eenmalig</th>
                    <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide text-right">Per jaar</th>
                    <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide text-right">Acties</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($quotes as $quote)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-5 py-3.5 font-mono text-xs font-medium text-gray-800">
                            {{ $quote->quote_number }}
                        </td>
                        <td class="px-5 py-3.5 font-medium text-gray-800">
                            {{ $quote->customer?->company_name ?? '—' }}
                        </td>
                        <td class="px-5 py-3.5 text-gray-500">{{ $quote->user?->name ?? '—' }}</td>
                        <td class="px-5 py-3.5">
                            @php
                                $colors = [
                                    'concept'     => 'bg-gray-100 text-gray-600',
                                    'verzonden'   => 'bg-blue-100 text-blue-700',
                                    'ondertekend' => 'bg-green-100 text-green-700',
                                    'verlopen'    => 'bg-orange-100 text-orange-700',
                                    'geannuleerd' => 'bg-red-100 text-red-700',
                                ];
                                $color = $colors[$quote->status] ?? 'bg-gray-100 text-gray-600';
                            @endphp
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $color }}">
                                {{ $statusLabels[$quote->status] ?? $quote->status }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 text-gray-500">{{ $quote->created_at->format('d-m-Y') }}</td>
                        <td class="px-5 py-3.5 text-gray-500">{{ $quote->valid_until->format('d-m-Y') }}</td>
                        <td class="px-5 py-3.5 text-right text-gray-700">
                            € {{ number_format($quote->total_onetime_excl_vat, 2, ',', '.') }}
                        </td>
                        <td class="px-5 py-3.5 text-right text-gray-700">
                            € {{ number_format($quote->total_yearly_excl_vat, 2, ',', '.') }}
                        </td>
                        <td class="px-5 py-3.5 text-right">
                            <div class="flex items-center justify-end gap-3">
                                <a href="{{ route('verkoper.quotes.show', $quote) }}"
                                   class="text-blue-600 hover:text-blue-800 font-medium">Bekijken</a>
                                @if($quote->status === 'concept')
                                    <a href="{{ route('verkoper.quotes.edit', $quote) }}"
                                       class="text-gray-500 hover:text-gray-700 font-medium">Bewerken</a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="px-5 py-14 text-center text-gray-400">
                            Geen offertes gevonden.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
