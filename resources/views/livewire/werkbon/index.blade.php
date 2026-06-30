<div>
    <div class="flex flex-col sm:flex-row gap-3 mb-5">
        <input wire:model.live.debounce.300ms="search" type="search"
               placeholder="Zoek op offertenummer of klant…"
               class="w-full sm:w-72 rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"/>
        <select wire:model.live="afgerondFilter"
                class="w-full sm:w-52 rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
            <option value="">Alle werkbonnen</option>
            <option value="0">Nog niet afgerond</option>
            <option value="1">Afgerond</option>
        </select>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">Offertenummer</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">Klant</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-600 hidden sm:table-cell">Afgerond</th>
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
                            @if($quote->werkbon_afgerond)
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                    {{ $quote->werkbon_afgerond_op?->format('d-m-Y') }}
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-500">
                                    Nog niet afgerond
                                </span>
                            @endif
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
                            Geen ondertekende offertes gevonden.
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
