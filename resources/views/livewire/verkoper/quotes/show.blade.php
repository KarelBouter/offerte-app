<div>
    <x-breadcrumb :items="[['label' => 'Offertes', 'route' => 'verkoper.offertes.index'], ['label' => $quote->quote_number]]"/>

    @if(session('success'))
    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)"
         x-show="show" x-transition
         class="mb-4 flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 text-sm px-4 py-3 rounded-lg">
        <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
        {{ session('success') }}
    </div>
    @endif

    @if(session('auto_download_pdf'))
    <a id="auto-pdf-link" href="{{ session('auto_download_pdf') }}" target="_blank" style="display:none;"></a>
    <script>document.addEventListener('DOMContentLoaded', function() { document.getElementById('auto-pdf-link').click(); });</script>
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
            @if(auth()->user()->canChangeQuoteStatus())
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
            @endif

            {{-- PDF download --}}
            @if(auth()->user()->canGeneratePdf())
            <a href="{{ route('verkoper.offertes.pdf', $quote) }}"
               target="_blank"
               class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium text-gray-600 bg-white border border-gray-300 hover:bg-gray-50">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                </svg>
                PDF downloaden
            </a>
            @endif

            {{-- Verstuur naar klant --}}
            @if(auth()->user()->canSendQuotes() && in_array($quote->status, ['concept', 'verzonden']))
            <div x-data="{ confirmSend: false }">
                <button @click="confirmSend = true"
                        class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium text-emerald-700 bg-emerald-50 border border-emerald-200 hover:bg-emerald-100">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    Verstuur naar klant
                </button>

                {{-- Confirmation modal --}}
                <div x-show="confirmSend" x-transition
                     class="fixed inset-0 z-50 flex items-center justify-center bg-black/40">
                    <div class="bg-white rounded-xl shadow-xl p-6 max-w-md w-full mx-4">
                        <h3 class="text-base font-semibold text-gray-800 mb-3">Offerte versturen</h3>
                        <p class="text-sm text-gray-600 mb-5">
                            Offerte wordt verstuurd naar
                            <strong>{{ $quote->customer->contact_email }}</strong>.<br>
                            De klant ontvangt een link om de offerte in te zien en te ondertekenen.
                            Weet je het zeker?
                        </p>
                        <div class="flex justify-end gap-3">
                            <button @click="confirmSend = false"
                                    class="px-4 py-2 text-sm text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                                Annuleren
                            </button>
                            <button wire:click="sendToClient" @click="confirmSend = false"
                                    class="px-4 py-2 text-sm font-medium text-white rounded-lg"
                                    style="background-color: #1B3A6B;">
                                Ja, versturen
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            {{-- Duplicate --}}
            @if(auth()->user()->canSendQuotes())
            <button wire:click="duplicate"
                    wire:confirm="Weet je zeker dat je deze offerte wilt dupliceren?"
                    class="px-3 py-2 rounded-lg text-sm font-medium text-gray-600 bg-white border border-gray-300 hover:bg-gray-50">
                Dupliceren
            </button>
            @endif

            {{-- Mede-ondertekenen namens Proud Innovations --}}
            @if($quote->status === 'ondertekend' && !$quote->cosigned_at && auth()->user()->canSendQuotes())
            <button
                wire:click="signOnBehalf"
                wire:confirm="Weet je zeker dat je deze offerte wilt mede-ondertekenen namens Proud Innovations? De PDF wordt daarna definitief bijgewerkt."
                class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium text-green-700 bg-green-50 border border-green-300 hover:bg-green-100 transition-colors">
                Ondertekenen namens PI
            </button>
            @endif

            @if($quote->cosigned_at)
            <div class="text-xs text-green-600 font-medium px-2">
                ✓ Mede-ondertekend door {{ $quote->cosigned_by }}<br>
                <span class="text-gray-400">{{ $quote->cosigned_at->format('d-m-Y H:i') }}</span>
            </div>
            @endif

            {{-- Edit (only for concept) --}}
            @if($quote->status === 'concept')
            <a href="{{ route('verkoper.offertes.edit', $quote) }}"
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
                <div class="overflow-x-auto">
                <table class="w-full text-sm min-w-[500px]">
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
            </div>
            @endif

            {{-- Servicecontract --}}
            @if($yearlyItems->isNotEmpty())
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-5 py-3 border-b border-gray-100 bg-gray-50">
                    <h2 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Servicecontract (jaarlijks)</h2>
                </div>
                <div class="overflow-x-auto">
                <table class="w-full text-sm min-w-[500px]">
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
            <a href="{{ route('verkoper.offertes.index') }}"
               class="flex items-center gap-2 text-sm text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Terug naar offertes
            </a>
        </div>
    </div>

    {{-- ── Versiegeschiedenis ──────────────────────────────────────────────── --}}
    <div class="mt-8 border-t border-gray-200 pt-6">
        @livewire('quotes.quote-versions', ['quote' => $quote])
    </div>

    {{-- ── Taken & notities ────────────────────────────────────────────────── --}}
    <div class="mt-6">
        <div class="flex items-center justify-between mb-3">
            <h2 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Taken & notities</h2>
            <button onclick="Livewire.dispatch('open-task-modal', { quoteId: {{ $quote->id }} })"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium text-white transition-colors"
                    style="background-color: #1B3A6B;">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                Taak toevoegen
            </button>
        </div>

        @if($quote->tasks->isEmpty())
            <div class="bg-white rounded-xl border border-gray-200 px-5 py-10 text-center">
                <svg class="w-8 h-8 mx-auto mb-2 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <p class="text-sm text-gray-400">Nog geen taken gekoppeld aan deze offerte.</p>
            </div>
        @else
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                <table class="w-full text-sm min-w-[500px]">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100 text-left">
                            <th class="px-5 py-2.5 text-xs font-semibold text-gray-500 uppercase tracking-wide">Status</th>
                            <th class="px-5 py-2.5 text-xs font-semibold text-gray-500 uppercase tracking-wide">Taak</th>
                            <th class="px-5 py-2.5 text-xs font-semibold text-gray-500 uppercase tracking-wide">Toegewezen aan</th>
                            <th class="px-5 py-2.5 text-xs font-semibold text-gray-500 uppercase tracking-wide">Deadline</th>
                            <th class="px-5 py-2.5 text-xs font-semibold text-gray-500 uppercase tracking-wide text-right">Actie</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($quote->tasks->sortBy('due_date') as $task)
                            @php
                                $sc = ['open'=>'bg-gray-100 text-gray-600','in_behandeling'=>'bg-blue-100 text-blue-700','afgerond'=>'bg-green-100 text-green-700'];
                                $sl = ['open'=>'Open','in_behandeling'=>'In behandeling','afgerond'=>'Afgerond'];
                            @endphp
                            <tr class="{{ $task->status === 'afgerond' ? 'opacity-60' : '' }} hover:bg-gray-50 transition-colors">
                                <td class="px-5 py-3">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $sc[$task->status] ?? 'bg-gray-100 text-gray-600' }}">
                                        {{ $sl[$task->status] ?? $task->status }}
                                    </span>
                                </td>
                                <td class="px-5 py-3">
                                    <a href="{{ route('taken.show', $task) }}"
                                       class="font-medium text-gray-800 hover:text-blue-600 {{ $task->status === 'afgerond' ? 'line-through' : '' }}">
                                        {{ $task->title }}
                                    </a>
                                    @if($task->createdBy)
                                        <p class="text-xs text-gray-400 mt-0.5">Door {{ $task->createdBy->name }}</p>
                                    @endif
                                </td>
                                <td class="px-5 py-3 text-gray-500 text-xs">
                                    {{ $task->assignedTo?->name ?? '—' }}
                                </td>
                                <td class="px-5 py-3 text-xs">
                                    @if($task->due_date)
                                        <span class="{{ $task->due_date->isPast() && $task->status !== 'afgerond' ? 'text-red-600 font-medium' : 'text-gray-500' }}">
                                            {{ $task->due_date->format('d-m-Y') }}
                                        </span>
                                    @else
                                        <span class="text-gray-300">—</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3 text-right">
                                    <a href="{{ route('taken.show', $task) }}"
                                       class="text-blue-600 hover:text-blue-800 text-xs font-medium">
                                        Bekijken
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                </div>
            </div>
        @endif
    </div>
</div>
