<div>
    @if(session('success'))
    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)"
         x-show="show" x-transition
         class="mb-4 flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 text-sm px-4 py-3 rounded-lg">
        <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
        {{ session('success') }}
    </div>
    @endif

    {{-- ── Header bar ──────────────────────────────────────────────────────── --}}
    <div class="flex items-start justify-between mb-6 gap-4">
        <div>
            <div class="flex items-center gap-3">
                <h1 class="text-xl font-semibold text-gray-800 font-mono">{{ $quote->quote_number }}</h1>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $statusColors[$quote->status] ?? 'bg-gray-100 text-gray-600' }}">
                    {{ $statusLabels[$quote->status] ?? $quote->status }}
                </span>
            </div>
            <p class="text-sm text-gray-400 mt-1">
                Aangemaakt op {{ $quote->created_at->format('d-m-Y') }}
                door {{ $quote->user?->name ?? '—' }}
                · Geldig tot {{ $quote->valid_until->format('d-m-Y') }}
            </p>
        </div>

        <div class="flex items-center gap-2 flex-shrink-0">
            {{-- Status dropdown --}}
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open"
                        class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium text-gray-600 bg-white border border-gray-300 hover:bg-gray-50">
                    Status wijzigen
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="open" @click.outside="open = false" x-transition
                     class="absolute right-0 mt-1 w-44 bg-white border border-gray-200 rounded-lg shadow-lg z-10 py-1">
                    @foreach($statusLabels as $val => $label)
                        <button wire:click="updateStatus('{{ $val }}')" @click="open = false"
                                class="w-full text-left px-4 py-2 text-sm hover:bg-gray-50
                                    {{ $quote->status === $val ? 'font-semibold text-blue-700' : 'text-gray-700' }}">
                            {{ $label }}
                        </button>
                    @endforeach
                </div>
            </div>

            {{-- Duplicate --}}
            <button wire:click="duplicate"
                    wire:confirm="Weet je zeker dat je deze offerte wilt dupliceren?"
                    class="px-3 py-2 rounded-lg text-sm font-medium text-gray-600 bg-white border border-gray-300 hover:bg-gray-50">
                Dupliceren
            </button>

            {{-- Edit (only for concept) --}}
            @if($quote->status === 'concept')
            <a href="{{ route('verkoper.quotes.edit', $quote) }}"
               class="px-3 py-2 rounded-lg text-sm font-medium text-white shadow-sm"
               style="background-color: #1B3A6B;">
                Bewerken
            </a>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        {{-- ── LEFT: items + notes ───────────────────────────────────────────── --}}
        <div class="lg:col-span-2 space-y-5">

            {{-- Eenmalige kosten --}}
            @if($onetimeItems->isNotEmpty())
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-5 py-3 border-b border-gray-100 bg-gray-50">
                    <h2 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Eenmalige kosten</h2>
                </div>
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-100 text-left">
                            <th class="px-5 py-2.5 text-xs font-semibold text-gray-500">Product</th>
                            <th class="px-5 py-2.5 text-xs font-semibold text-gray-500 text-center">Aantal</th>
                            <th class="px-5 py-2.5 text-xs font-semibold text-gray-500 text-right">Eenheidsprijs</th>
                            <th class="px-5 py-2.5 text-xs font-semibold text-gray-500 text-right">Totaal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($onetimeItems as $item)
                        <tr class="{{ $item->is_auto_added ? 'bg-blue-50/30' : '' }}">
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-2">
                                    <span class="font-medium text-gray-800">{{ $item->product->name }}</span>
                                    @if($item->is_auto_added)
                                        <span class="text-xs bg-blue-100 text-blue-600 px-1.5 py-0.5 rounded">Auto</span>
                                    @endif
                                </div>
                                @if($item->auto_added_reason)
                                    <p class="text-xs text-gray-400 mt-0.5">{{ $item->auto_added_reason }}</p>
                                @endif
                            </td>
                            <td class="px-5 py-3 text-center text-gray-600">{{ $item->quantity }}</td>
                            <td class="px-5 py-3 text-right text-gray-600">
                                € {{ number_format($item->unit_price_snapshot, 2, ',', '.') }}
                            </td>
                            <td class="px-5 py-3 text-right font-semibold text-gray-800">
                                € {{ number_format($item->unit_price_snapshot * $item->quantity, 2, ',', '.') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="border-t-2 border-gray-200 bg-gray-50">
                        <tr>
                            <td colspan="3" class="px-5 py-3 text-sm font-semibold text-gray-700">Subtotaal excl. BTW</td>
                            <td class="px-5 py-3 text-right text-sm font-bold text-gray-800">
                                € {{ number_format($quote->total_onetime_excl_vat, 2, ',', '.') }}
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3" class="px-5 py-2 text-xs text-gray-500">BTW 21%</td>
                            <td class="px-5 py-2 text-right text-xs text-gray-500">
                                € {{ number_format($quote->total_onetime_excl_vat * 0.21, 2, ',', '.') }}
                            </td>
                        </tr>
                        <tr class="border-t border-gray-200">
                            <td colspan="3" class="px-5 py-3 text-sm font-bold text-gray-800">Totaal incl. BTW</td>
                            <td class="px-5 py-3 text-right text-sm font-bold text-gray-800">
                                € {{ number_format($quote->total_onetime_excl_vat * 1.21, 2, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            @endif

            {{-- Servicecontract --}}
            @if($yearlyItems->isNotEmpty())
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-5 py-3 border-b border-gray-100 bg-gray-50">
                    <h2 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Servicecontract (jaarlijks)</h2>
                </div>
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-100 text-left">
                            <th class="px-5 py-2.5 text-xs font-semibold text-gray-500">Product</th>
                            <th class="px-5 py-2.5 text-xs font-semibold text-gray-500 text-center">Aantal</th>
                            <th class="px-5 py-2.5 text-xs font-semibold text-gray-500 text-right">Per jaar</th>
                            <th class="px-5 py-2.5 text-xs font-semibold text-gray-500 text-right">Totaal / jaar</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($yearlyItems as $item)
                        <tr>
                            <td class="px-5 py-3 font-medium text-gray-800">{{ $item->product->name }}</td>
                            <td class="px-5 py-3 text-center text-gray-600">{{ $item->quantity }}</td>
                            <td class="px-5 py-3 text-right text-gray-600">
                                € {{ number_format($item->unit_price_snapshot, 2, ',', '.') }}
                            </td>
                            <td class="px-5 py-3 text-right font-semibold text-gray-800">
                                € {{ number_format($item->unit_price_snapshot * $item->quantity, 2, ',', '.') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="border-t-2 border-gray-200 bg-gray-50">
                        <tr>
                            <td colspan="3" class="px-5 py-3 text-sm font-bold text-gray-800">Per jaar excl. BTW</td>
                            <td class="px-5 py-3 text-right text-sm font-bold text-gray-800">
                                € {{ number_format($quote->total_yearly_excl_vat, 2, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            @endif

            {{-- Op offerte items --}}
            @if($onQuoteItems->isNotEmpty())
            <div class="bg-amber-50 rounded-xl border border-amber-200 overflow-hidden">
                <div class="px-5 py-3 border-b border-amber-200">
                    <h2 class="text-sm font-semibold text-amber-800 uppercase tracking-wide">Op offerte (prijs op aanvraag)</h2>
                </div>
                <div class="p-4 space-y-2">
                    @foreach($onQuoteItems as $item)
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-amber-900 font-medium">{{ $item->product->name }}</span>
                        <span class="text-amber-700">× {{ $item->quantity }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Notes --}}
            @if($quote->notes)
            <div class="bg-gray-50 rounded-xl border border-gray-200 p-5">
                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Interne notities</h3>
                <p class="text-sm text-gray-700 leading-relaxed">{{ $quote->notes }}</p>
            </div>
            @endif
        </div>

        {{-- ── RIGHT: Customer + summary sidebar ──────────────────────────── --}}
        <div class="space-y-5">

            {{-- Customer card --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                <h2 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3">Klant</h2>
                <div class="space-y-2 text-sm">
                    <p class="font-semibold text-gray-800">{{ $quote->customer?->company_name ?? '—' }}</p>
                    <p class="text-gray-500">{{ $quote->customer?->address }}</p>
                    <p class="text-gray-400 text-xs">KvK: {{ $quote->customer?->kvk_number }}</p>

                    <div class="border-t border-gray-100 pt-3 mt-3">
                        <p class="font-medium text-gray-700">{{ $quote->customer?->contact_name }}</p>
                        <a href="mailto:{{ $quote->customer?->contact_email }}"
                           class="text-blue-600 hover:underline text-xs">{{ $quote->customer?->contact_email }}</a>
                        @if($quote->customer?->contact_phone)
                            <p class="text-gray-400 text-xs mt-0.5">{{ $quote->customer->contact_phone }}</p>
                        @endif
                    </div>

                    @if($quote->installation_address)
                    <div class="border-t border-gray-100 pt-3 mt-3">
                        <p class="text-xs text-gray-400 mb-1">Installatieadres</p>
                        <p class="text-gray-600">{{ $quote->installation_address }}</p>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Price summary --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-4 py-3 border-b border-gray-100" style="background-color: #1B3A6B;">
                    <h2 class="text-sm font-semibold text-white">Samenvatting</h2>
                </div>
                <div class="p-4 space-y-3 text-sm">
                    @if($quote->total_onetime_excl_vat > 0)
                    <div>
                        <div class="flex justify-between text-gray-500 text-xs">
                            <span>Eenmalig excl. BTW</span>
                            <span>€ {{ number_format($quote->total_onetime_excl_vat, 2, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-gray-500 text-xs">
                            <span>BTW 21%</span>
                            <span>€ {{ number_format($quote->total_onetime_excl_vat * 0.21, 2, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between font-bold text-gray-800 border-t border-gray-100 pt-2 mt-1">
                            <span>Eenmalig incl. BTW</span>
                            <span>€ {{ number_format($quote->total_onetime_excl_vat * 1.21, 2, ',', '.') }}</span>
                        </div>
                    </div>
                    @endif

                    @if($quote->total_yearly_excl_vat > 0)
                    <div class="border-t border-gray-100 pt-3">
                        <div class="flex justify-between text-gray-500 text-xs">
                            <span>Per jaar excl. BTW</span>
                            <span>€ {{ number_format($quote->total_yearly_excl_vat, 2, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between font-bold text-gray-800 border-t border-gray-100 pt-2 mt-1">
                            <span>Per jaar incl. BTW</span>
                            <span>€ {{ number_format($quote->total_yearly_excl_vat * 1.21, 2, ',', '.') }}</span>
                        </div>
                    </div>
                    @endif

                    @if($onQuoteItems->isNotEmpty())
                    <p class="text-xs text-amber-600 border-t border-gray-100 pt-3">
                        + {{ $onQuoteItems->count() }} {{ $onQuoteItems->count() === 1 ? 'post' : 'posten' }} op offerte (prijs op aanvraag)
                    </p>
                    @endif
                </div>
            </div>

            {{-- Quote meta --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 text-sm space-y-2">
                <h2 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3">Offerte details</h2>
                <div class="flex justify-between">
                    <span class="text-gray-500">Aangemaakt door</span>
                    <span class="text-gray-700 font-medium">{{ $quote->user?->name ?? '—' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Aangemaakt op</span>
                    <span class="text-gray-700">{{ $quote->created_at->format('d-m-Y') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Geldig tot</span>
                    <span class="{{ $quote->valid_until->isPast() ? 'text-red-600 font-medium' : 'text-gray-700' }}">
                        {{ $quote->valid_until->format('d-m-Y') }}
                        @if($quote->valid_until->isPast()) (verlopen) @endif
                    </span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Laatste wijziging</span>
                    <span class="text-gray-700">{{ $quote->updated_at->format('d-m-Y H:i') }}</span>
                </div>
            </div>

            {{-- Back link --}}
            <a href="{{ route('verkoper.quotes.index') }}"
               class="flex items-center gap-2 text-sm text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Terug naar offertes
            </a>
        </div>
    </div>
</div>
