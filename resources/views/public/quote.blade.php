<x-layouts.public title="Offerte {{ $quote->quote_number }}">
<div class="space-y-8">

    {{-- Header --}}
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Offerte {{ $quote->quote_number }}</h1>
                <p class="text-sm text-gray-500 mt-1">
                    Opgesteld op {{ $quote->created_at->format('d-m-Y') }}
                    @if($quote->valid_until)
                        &middot; Geldig t/m {{ \Carbon\Carbon::parse($quote->valid_until)->format('d-m-Y') }}
                    @endif
                </p>
            </div>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                {{ $quote->status === 'ondertekend' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700' }}">
                {{ ucfirst($quote->status) }}
            </span>
        </div>
    </div>

    {{-- Klantgegevens --}}
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-4">Klantgegevens</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-2 gap-x-8 text-sm">
            <div>
                <span class="text-gray-500">Bedrijfsnaam</span>
                <p class="font-medium text-gray-800">{{ $quote->customer->company_name }}</p>
            </div>
            <div>
                <span class="text-gray-500">Contactpersoon</span>
                <p class="font-medium text-gray-800">{{ $quote->customer->contact_name }}</p>
            </div>
            <div>
                <span class="text-gray-500">E-mail</span>
                <p class="font-medium text-gray-800">{{ $quote->customer->contact_email }}</p>
            </div>
            @if($quote->customer->phone)
            <div>
                <span class="text-gray-500">Telefoon</span>
                <p class="font-medium text-gray-800">{{ $quote->customer->phone }}</p>
            </div>
            @endif
            @if($quote->customer->address)
            <div class="sm:col-span-2">
                <span class="text-gray-500">Adres</span>
                <p class="font-medium text-gray-800">
                    {{ $quote->customer->address }}
                    @if($quote->customer->postal_code || $quote->customer->city)
                        &mdash; {{ $quote->customer->postal_code }} {{ $quote->customer->city }}
                    @endif
                </p>
            </div>
            @endif
        </div>
    </div>

    {{-- Eenmalige kosten --}}
    @if($onetimeItems->count())
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="text-sm font-semibold text-gray-700">Eenmalige kosten</h2>
        </div>
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Product</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wide">Aantal</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wide">Stuksprijs</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wide">Totaal</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($onetimeItems as $item)
                <tr>
                    <td class="px-6 py-3 text-gray-800">
                        {{ $item->product->name }}
                        @if($item->product->description)
                            <p class="text-xs text-gray-400 mt-0.5">{{ $item->product->description }}</p>
                        @endif
                    </td>
                    <td class="px-6 py-3 text-right text-gray-700">{{ $item->quantity }}</td>
                    <td class="px-6 py-3 text-right text-gray-700">€ {{ number_format($item->unit_price, 2, ',', '.') }}</td>
                    <td class="px-6 py-3 text-right font-medium text-gray-900">€ {{ number_format($item->quantity * $item->unit_price, 2, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot class="bg-gray-50 border-t border-gray-200">
                <tr>
                    <td colspan="3" class="px-6 py-3 text-right text-sm font-semibold text-gray-700">Subtotaal eenmalig</td>
                    <td class="px-6 py-3 text-right font-bold text-gray-900">
                        € {{ number_format($onetimeItems->sum(fn($i) => $i->quantity * $i->unit_price), 2, ',', '.') }}
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
    @endif

    {{-- Jaarlijkse kosten --}}
    @if($yearlyItems->count())
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="text-sm font-semibold text-gray-700">Jaarlijkse kosten</h2>
        </div>
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Product</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wide">Aantal</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wide">Stuksprijs/jaar</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wide">Totaal/jaar</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($yearlyItems as $item)
                <tr>
                    <td class="px-6 py-3 text-gray-800">
                        {{ $item->product->name }}
                        @if($item->product->description)
                            <p class="text-xs text-gray-400 mt-0.5">{{ $item->product->description }}</p>
                        @endif
                    </td>
                    <td class="px-6 py-3 text-right text-gray-700">{{ $item->quantity }}</td>
                    <td class="px-6 py-3 text-right text-gray-700">€ {{ number_format($item->unit_price, 2, ',', '.') }}</td>
                    <td class="px-6 py-3 text-right font-medium text-gray-900">€ {{ number_format($item->quantity * $item->unit_price, 2, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot class="bg-gray-50 border-t border-gray-200">
                <tr>
                    <td colspan="3" class="px-6 py-3 text-right text-sm font-semibold text-gray-700">Subtotaal per jaar</td>
                    <td class="px-6 py-3 text-right font-bold text-gray-900">
                        € {{ number_format($yearlyItems->sum(fn($i) => $i->quantity * $i->unit_price), 2, ',', '.') }}
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
    @endif

    {{-- Prijs op offerte --}}
    @if($onQuoteItems->count())
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="text-sm font-semibold text-gray-700">Overige diensten (prijs op aanvraag)</h2>
        </div>
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Product</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wide">Aantal</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($onQuoteItems as $item)
                <tr>
                    <td class="px-6 py-3 text-gray-800">{{ $item->product->name }}</td>
                    <td class="px-6 py-3 text-right text-gray-700">{{ $item->quantity }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    {{-- Opmerkingen --}}
    @if($quote->notes)
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3">Opmerkingen</h2>
        <p class="text-sm text-gray-700 whitespace-pre-line">{{ $quote->notes }}</p>
    </div>
    @endif

    {{-- Akkoord knop (Phase 2 placeholder) --}}
    @if($quote->status !== 'ondertekend')
    <div class="bg-blue-50 border border-blue-200 rounded-xl p-6 text-center">
        <h2 class="text-base font-semibold text-blue-900 mb-2">Akkoord met deze offerte?</h2>
        <p class="text-sm text-blue-700 mb-4">
            Digitaal ondertekenen wordt binnenkort beschikbaar. Neem voor nu contact op met uw accountmanager.
        </p>
        <a href="mailto:info@proudinnovations.nl?subject=Akkoord offerte {{ $quote->quote_number }}"
           class="inline-flex items-center gap-2 bg-blue-700 hover:bg-blue-800 text-white font-medium text-sm px-5 py-2.5 rounded-lg transition-colors">
            Akkoord per e-mail bevestigen
        </a>
    </div>
    @else
    <div class="bg-green-50 border border-green-200 rounded-xl p-6 text-center">
        <p class="text-green-800 font-semibold">Deze offerte is reeds ondertekend. Bedankt!</p>
    </div>
    @endif

</div>
</x-layouts.public>
