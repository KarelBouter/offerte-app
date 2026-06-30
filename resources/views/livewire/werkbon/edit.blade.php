<div x-on:livewire-navigate-end.window="$wire.$refresh()">
    {{-- lg:w-64 is not in the compiled CSS (new file), so the responsive column
         width is enforced via an inline media query matching the layout pattern. --}}
    <style>
        @media (min-width: 1024px) { .werkbon-info-col { width: 16rem; } }
    </style>

    {{-- Breadcrumb --}}
    <x-breadcrumb :items="[
        ['label' => 'Werkbonnen', 'route' => 'werkbon.index'],
        ['label' => $quote->quote_number],
    ]"/>

    <div class="flex flex-col lg:flex-row lg:items-start gap-6">

        {{-- Linker kolom: quote-info --}}
        <div class="werkbon-info-col w-full flex-shrink-0 space-y-4">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                <p class="text-xs font-semibold uppercase tracking-wide text-gray-400 mb-3">Offerte</p>
                <p class="font-semibold text-gray-900">{{ $quote->quote_number }} <span class="text-gray-400 font-normal text-sm">v{{ $quote->revision }}</span></p>
                <p class="text-sm text-gray-600 mt-1">{{ $quote->customer?->company_name ?? '—' }}</p>
                @if($quote->installation_address)
                    <p class="text-xs text-gray-500 mt-1">{{ $quote->installation_address }}</p>
                @endif
                <div class="mt-3 pt-3 border-t border-gray-100">
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
                </div>
            </div>

            @if($quote->werkbon_laatst_bewerkt_op)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-400 mb-2">Laatste wijziging</p>
                    <p class="text-sm text-gray-700">{{ $quote->werkbon_laatst_bewerkt_op->format('d-m-Y H:i') }}</p>
                    @if($quote->werkbonBewerker)
                        <p class="text-xs text-gray-500 mt-0.5">{{ $quote->werkbonBewerker->name }}</p>
                    @endif
                </div>
            @endif

            {{-- Afronding --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4" x-data="{ open: @json($werkbonAfgerond) }">
                <p class="text-xs font-semibold uppercase tracking-wide text-gray-400 mb-3">Afronding</p>

                @if($quote->werkbon_afgerond)
                    <div class="flex items-start gap-2 mb-3 p-2 rounded-lg bg-green-50 border border-green-200">
                        <svg class="w-4 h-4 text-green-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        <div>
                            <p class="text-xs font-medium text-green-800">Afgerond op {{ $quote->werkbon_afgerond_op->format('d-m-Y') }}</p>
                            <p class="text-xs text-green-700 mt-0.5">door {{ $quote->werkbon_afgerond_door }}</p>
                        </div>
                    </div>
                @endif

                <label class="flex items-center gap-2.5 cursor-pointer">
                    <input type="checkbox" wire:model.live="werkbonAfgerond"
                           x-model="open"
                           class="rounded border-gray-300 text-green-600 shadow-sm focus:ring-green-500"/>
                    <span class="text-sm font-medium text-gray-700">Werkbon afgerond</span>
                </label>

                <div x-show="open" x-cloak class="mt-3 space-y-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Datum afronding <span class="text-red-500">*</span></label>
                        <input type="date" wire:model="werkbonAfgerondOp"
                               class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-green-500 focus:ring-green-500"/>
                        @error('werkbonAfgerondOp') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Naam monteur / uitgevoerd door <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="werkbonAfgerondDoor"
                               placeholder="Naam van de monteur of uitvoerder"
                               class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-green-500 focus:ring-green-500"/>
                        @error('werkbonAfgerondDoor') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- Werkbon PDF --}}
            @if(auth()->user()->canGeneratePdf())
                <a href="{{ route('verkoper.offertes.werkbon', $quote) }}"
                   class="flex items-center justify-center gap-2 w-full px-4 py-2.5 rounded-lg text-sm font-medium text-white transition-colors"
                   style="background-color: #1B3A6B;"
                   target="_blank">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Werkbon PDF
                </a>
            @endif
        </div>

        {{-- Rechter kolom: items editor --}}
        <div class="flex-1 min-w-0">
            <div class="space-y-4">
                @foreach($items as $item)
                    @php
                        $key     = (string) $item->product_id;
                        $itemKey = (string) $item->id;
                        $product = $item->product;
                        $isCable = $product && str_contains(strtolower($product->name), 'kabel');
                        $verborgenStr = $werkbonVerborgen[$key] ?? '';
                    @endphp
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        {{-- Item header --}}
                        <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100">
                            <div class="flex items-center gap-3 min-w-0">
                                <span class="text-sm font-semibold text-gray-900 truncate">{{ $product->name }}</span>
                                <span class="text-xs text-gray-400 flex-shrink-0">× {{ $item->quantity }}</span>
                                @if($product->werkbon_zichtbaarheid === 'verbergen')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-500 flex-shrink-0">
                                        standaard verborgen
                                    </span>
                                @elseif($product->werkbon_zichtbaarheid === 'altijd')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-600 flex-shrink-0">
                                        altijd tonen
                                    </span>
                                @endif
                            </div>
                            {{-- Werkbon zichtbaarheid (3 standen) --}}
                            <div class="flex-shrink-0 ml-3">
                                <select wire:model.live="werkbonVerborgen.{{ $key }}"
                                        class="rounded-lg border-gray-300 text-xs shadow-sm focus:border-blue-500 focus:ring-blue-500
                                               {{ $verborgenStr === '1' ? 'bg-red-50 text-red-700' : ($verborgenStr === '0' ? 'bg-green-50 text-green-700' : '') }}">
                                    <option value="">Automatisch</option>
                                    <option value="0" @selected($verborgenStr === '0')>Geforceerd tonen</option>
                                    <option value="1" @selected($verborgenStr === '1')>Verbergen</option>
                                </select>
                            </div>
                        </div>

                        <div class="px-4 py-3 space-y-3">
                            {{-- Product installatie-instructie (readonly) --}}
                            @if($product->installatie_instructie)
                                <div class="rounded-lg bg-blue-50 border border-blue-100 px-3 py-2">
                                    <p class="text-xs font-medium text-blue-700 mb-0.5">Standaard instructie</p>
                                    <p class="text-xs text-blue-800 whitespace-pre-wrap">{{ $product->installatie_instructie }}</p>
                                </div>
                            @endif

                            {{-- Aantekening (vaste opties) --}}
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Vaste aantekening</label>
                                <select wire:model.live="werkbonAantekeningen.{{ $key }}"
                                        class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">— Geen —</option>
                                    @foreach($aantekeningOpties as $val => $label)
                                        <option value="{{ $val }}" @selected(($werkbonAantekeningen[$key] ?? '') === $val)>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Vrije installatie-notitie --}}
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Vrije notitie</label>
                                <textarea wire:model.lazy="installatieNotities.{{ $key }}"
                                          rows="2"
                                          placeholder="Vrije notitie voor op de werkbon…"
                                          class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500 resize-y"></textarea>
                            </div>

                            {{-- Kabelruns: bewerkbare namen --}}
                            @if($item->cable_runs && count($item->cable_runs) > 0)
                                <div>
                                    <p class="text-xs font-medium text-gray-600 mb-2">Kabelruns</p>
                                    <div class="space-y-1.5">
                                        @foreach($item->cable_runs as $i => $run)
                                            @php $meters = is_array($run) ? ($run['meters'] ?? 0) : $run; @endphp
                                            <div class="flex items-center gap-2">
                                                <input type="text"
                                                       wire:model.lazy="cableRunNames.{{ $itemKey }}.{{ $i }}"
                                                       placeholder="Naam / locatie (bijv. 'gang naar serverruimte')"
                                                       class="flex-1 rounded-lg border-gray-300 text-xs shadow-sm focus:border-blue-500 focus:ring-blue-500"/>
                                                <span class="text-xs text-gray-500 flex-shrink-0 w-16 text-right">{{ $meters }} m</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6 flex items-center justify-between">
                <a href="{{ route('werkbon.index') }}" wire:navigate
                   class="text-sm text-gray-500 hover:text-gray-700">← Terug naar overzicht</a>
                <button wire:click="save"
                        wire:loading.attr="disabled"
                        class="px-6 py-2.5 rounded-lg text-sm font-medium text-white shadow-sm transition-opacity disabled:opacity-60"
                        style="background-color: #1B3A6B;">
                    <span wire:loading.remove>Opslaan</span>
                    <span wire:loading>Opslaan…</span>
                </button>
            </div>
        </div>
    </div>
</div>
