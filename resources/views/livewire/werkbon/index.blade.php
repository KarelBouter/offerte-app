<div>
    <div class="flex flex-col sm:flex-row sm:items-center gap-3 mb-5">
        <input wire:model.live.debounce.300ms="search" type="search"
               placeholder="Zoek op offertenummer of klant…"
               class="w-full sm:w-72 rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"/>
        <select wire:model.live="statusFilter"
                class="w-full sm:w-48 rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
            <option value="">Alle statussen</option>
            <option value="concept">Concept</option>
            <option value="verstuurd">Verstuurd</option>
            <option value="ondertekend">Ondertekend</option>
            <option value="verlopen">Verlopen</option>
            <option value="geannuleerd">Geannuleerd</option>
        </select>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">Offertenummer</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">Klant</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-600 hidden sm:table-cell">Status</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-600 hidden md:table-cell">Werkbon bijgewerkt</th>
                    <th class="px-4 py-3 text-right font-medium text-gray-600"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($quotes as $quote)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-4 py-3 font-medium text-gray-900">
                            {{ $quote->quote_number }}
                            <span class="ml-1 text-xs text-gray-400">v{{ $quote->revision }}</span>
                        </td>
                        <td class="px-4 py-3 text-gray-700">{{ $quote->customer?->company_name ?? '—' }}</td>
                        <td class="px-4 py-3 hidden sm:table-cell">
                            @php
                                $statusClass = match($quote->status) {
                                    'concept'      => 'bg-yellow-100 text-yellow-800',
                                    'verstuurd'    => 'bg-blue-100 text-blue-800',
                                    'ondertekend'  => 'bg-green-100 text-green-800',
                                    'verlopen'     => 'bg-gray-100 text-gray-600',
                                    'geannuleerd'  => 'bg-red-100 text-red-700',
                                    default        => 'bg-gray-100 text-gray-600',
                                };
                            @endphp
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $statusClass }}">
                                {{ ucfirst($quote->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 hidden md:table-cell text-gray-500 text-xs">
                            @if($quote->werkbon_laatst_bewerkt_op)
                                {{ $quote->werkbon_laatst_bewerkt_op->format('d-m-Y H:i') }}
                                @if($quote->werkbonBewerker)
                                    <span class="text-gray-400">· {{ $quote->werkbonBewerker->name }}</span>
                                @endif
                            @else
                                <span class="text-gray-400">Nog niet bewerkt</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('werkbon.edit', $quote) }}" wire:navigate
                               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium text-white transition-colors"
                               style="background-color: #1B3A6B;">
                                Werkbon bewerken
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-10 text-center text-sm text-gray-400">
                            Geen offertes gevonden.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($quotes->hasPages())
        <div class="mt-4">{{ $quotes->links() }}</div>
    @endif
</div>
