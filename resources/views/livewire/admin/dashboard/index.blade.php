<div class="space-y-6">
    {{-- Stat tiles --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach([
            ['label' => 'Actieve producten',    'value' => $totalProducts,                              'color' => 'bg-blue-50 text-blue-700'],
            ['label' => 'Totaal offertes',       'value' => $totalQuotes,                                'color' => 'bg-purple-50 text-purple-700'],
            ['label' => 'Offertes deze maand',   'value' => $quotesMonth,                               'color' => 'bg-amber-50 text-amber-700'],
            ['label' => 'Omzet deze maand',      'value' => '€ '.number_format($revenueMonth, 0, ',', '.'), 'color' => 'bg-green-50 text-green-700'],
        ] as $tile)
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">{{ $tile['label'] }}</p>
            <p class="text-2xl font-bold {{ $tile['color'] }} px-2 py-0.5 rounded-lg inline-block mt-1">{{ $tile['value'] }}</p>
        </div>
        @endforeach
    </div>

    {{-- Recent quotes --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-5 py-3 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
            <h2 class="text-sm font-semibold text-gray-700">Recente offertes</h2>
            <a href="{{ route('verkoper.quotes.index') }}" class="text-xs text-blue-600 hover:underline">Alle offertes →</a>
        </div>
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100 text-left">
                    <th class="px-5 py-2.5 text-xs font-semibold text-gray-500">Nummer</th>
                    <th class="px-5 py-2.5 text-xs font-semibold text-gray-500">Klant</th>
                    <th class="px-5 py-2.5 text-xs font-semibold text-gray-500">Verkoper</th>
                    <th class="px-5 py-2.5 text-xs font-semibold text-gray-500">Status</th>
                    <th class="px-5 py-2.5 text-xs font-semibold text-gray-500">Datum</th>
                    <th class="px-5 py-2.5 text-xs font-semibold text-gray-500 text-right">Totaal eenmalig</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($recentQuotes as $q)
                @php
                    $colors = ['concept'=>'bg-gray-100 text-gray-600','verzonden'=>'bg-blue-100 text-blue-700','ondertekend'=>'bg-green-100 text-green-700','verlopen'=>'bg-orange-100 text-orange-700','geannuleerd'=>'bg-red-100 text-red-700'];
                    $labels = ['concept'=>'Concept','verzonden'=>'Verzonden','ondertekend'=>'Ondertekend','verlopen'=>'Verlopen','geannuleerd'=>'Geannuleerd'];
                @endphp
                <tr class="hover:bg-gray-50">
                    <td class="px-5 py-3 font-mono text-xs font-medium">
                        <a href="{{ route('verkoper.quotes.show', $q) }}" class="text-blue-600 hover:underline">{{ $q->quote_number }}</a>
                    </td>
                    <td class="px-5 py-3 font-medium text-gray-800">{{ $q->customer?->company_name ?? '—' }}</td>
                    <td class="px-5 py-3 text-gray-500">{{ $q->user?->name ?? '—' }}</td>
                    <td class="px-5 py-3">
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $colors[$q->status] ?? 'bg-gray-100 text-gray-600' }}">
                            {{ $labels[$q->status] ?? $q->status }}
                        </span>
                    </td>
                    <td class="px-5 py-3 text-gray-400">{{ $q->created_at->format('d-m-Y') }}</td>
                    <td class="px-5 py-3 text-right text-gray-700">€ {{ number_format($q->total_onetime_excl_vat, 2, ',', '.') }}</td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-5 py-10 text-center text-gray-400">Nog geen offertes.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Products on quote --}}
    @if($productsOnQuote->isNotEmpty())
    <div class="bg-amber-50 rounded-xl border border-amber-200 overflow-hidden">
        <div class="px-5 py-3 border-b border-amber-200">
            <h2 class="text-sm font-semibold text-amber-800">Producten 'op offerte' — prijzen nog niet ingevuld</h2>
        </div>
        <div class="p-4 flex flex-wrap gap-2">
            @foreach($productsOnQuote as $p)
            <a href="{{ route('admin.products.edit', $p) }}"
               class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-medium bg-amber-100 text-amber-800 hover:bg-amber-200 transition-colors">
                {{ $p->name }}
            </a>
            @endforeach
        </div>
    </div>
    @endif
</div>
