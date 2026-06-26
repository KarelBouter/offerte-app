<div
    x-data="{ notifyMessage: null }"
    x-on:notify.window="notifyMessage = $event.detail.message; setTimeout(() => notifyMessage = null, 3500)"
>
    <x-breadcrumb :items="[['label' => 'Beheer', 'route' => 'beheer.dashboard'], ['label' => 'Afhankelijkheden']]"/>

    {{-- Toast --}}
    <div
        x-show="notifyMessage"
        x-transition.opacity
        class="fixed top-5 right-5 z-50 flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 rounded-lg px-4 py-3 text-sm shadow-md"
        style="display:none"
    >
        <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
        <span x-text="notifyMessage"></span>
    </div>

    {{-- Info block --}}
    <div class="mb-6 flex gap-3 rounded-xl border border-blue-200 p-4" style="background-color: #EFF6FF;">
        <svg class="w-5 h-5 text-blue-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <div class="space-y-1">
            <p class="text-sm text-blue-900">Afhankelijkheden bepalen welke producten automatisch worden toegevoegd als een verkoper een bepaald product kiest.</p>
            <p class="text-sm text-blue-700">Voorbeeld: als je Switch X instelt met een afhankelijkheid naar Netwerkkast, wordt de Netwerkkast automatisch toegevoegd zodra een verkoper Switch X selecteert in de configurator.</p>
        </div>
    </div>

    {{-- Stap 1: Trigger-product --}}
    <div class="mb-6">
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
            Stap 1: Kies het product dat de regel activeert (het trigger-product)
        </label>
        <select wire:model.live="selectedProductId"
                class="rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500 min-w-80">
            <option value="">Selecteer een product...</option>
            @foreach($products->groupBy('category') as $cat => $items)
                <optgroup label="{{ $cat }}">
                    @foreach($items as $p)
                        <option value="{{ $p->id }}">{{ $p->name }}</option>
                    @endforeach
                </optgroup>
            @endforeach
        </select>
        <p class="mt-1.5 text-xs text-gray-400">Dit is het product dat de verkoper kiest in de configurator.</p>
    </div>

    {{-- Stap 2 header --}}
    <div class="flex items-start justify-between mb-4">
        <div>
            @if($selectedProductId)
                <h2 class="text-sm font-semibold text-gray-700">
                    Stap 2: Regels voor <span class="text-blue-700">{{ $selectedProductName }}</span> —
                    wat wordt er automatisch toegevoegd als dit product gekozen wordt?
                </h2>
            @else
                <p class="text-sm text-gray-400 italic">← Kies eerst een trigger-product hierboven om de regels te beheren.</p>
            @endif
        </div>
        @if($selectedProductId)
            <div class="flex items-center gap-2 flex-shrink-0 ml-4">
                <button wire:click="openTestModal"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium text-green-700 bg-green-50 border border-green-200 hover:bg-green-100 transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                    </svg>
                    Regels testen
                </button>
                <button wire:click="openCreate"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium text-white shadow-sm transition-colors"
                        style="background-color: #1B3A6B;">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Nieuwe regel
                </button>
            </div>
        @endif
    </div>

    {{-- Tabel of lege staat --}}
    @if($selectedProductId)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200 text-left">
                        <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide w-40">Regeltype</th>
                        <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Omschrijving</th>
                        <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide text-right w-48">Acties</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($dependencies as $dep)
                        @php
                            $omschrijving = match ($dep->rule_type) {
                                'REQUIRED'            => 'Voegt automatisch ' . ($dep->resulting_quantity ?? 1) . ' stuks ' . ($dep->dependsOnProduct?->name ?? '?') . ' toe',
                                'REQUIRED_CALCULATED' => 'Berekent automatisch het aantal ' . ($dep->dependsOnProduct?->name ?? '?') . ' op basis van formule ' . ($dep->resulting_quantity_formula ?? '?'),
                                'THRESHOLD_SWITCH'    => 'Bij ' . ($dep->trigger_quantity_min ?? '?') . '–' . ($dep->trigger_quantity_max ?? '∞') . ' stuks: vervangt ' . ($dep->replacesProduct?->name ?? '?') . ' door ' . ($dep->dependsOnProduct?->name ?? '?'),
                                'RECOMMENDED'         => 'Stelt ' . ($dep->dependsOnProduct?->name ?? '?') . ' voor (verkoper kan afvinken)',
                                'EXCLUDES'            => 'Kan niet gecombineerd worden met ' . ($dep->dependsOnProduct?->name ?? '?'),
                                default               => '—',
                            };
                        @endphp
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-5 py-3.5">
                                <span @class([
                                    'inline-flex items-center px-2 py-0.5 rounded text-xs font-medium whitespace-nowrap',
                                    'bg-red-100 text-red-700'       => $dep->rule_type === 'EXCLUDES',
                                    'bg-blue-100 text-blue-700'     => $dep->rule_type === 'REQUIRED',
                                    'bg-purple-100 text-purple-700' => $dep->rule_type === 'REQUIRED_CALCULATED',
                                    'bg-amber-100 text-amber-700'   => $dep->rule_type === 'THRESHOLD_SWITCH',
                                    'bg-green-100 text-green-700'   => $dep->rule_type === 'RECOMMENDED',
                                ])>
                                    {{ $ruleLabels[$dep->rule_type] ?? $dep->rule_type }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-gray-700">{{ $omschrijving }}</td>
                            <td class="px-5 py-3.5 text-right">
                                <div class="flex items-center justify-end gap-4">
                                    <button wire:click="openTestModal"
                                            class="text-green-600 hover:text-green-800 font-medium transition-colors">
                                        Testen
                                    </button>
                                    <button wire:click="openEdit({{ $dep->id }})"
                                            class="text-blue-600 hover:text-blue-800 font-medium transition-colors">
                                        Bewerken
                                    </button>
                                    <button wire:click="delete({{ $dep->id }})"
                                            wire:confirm="Regel verwijderen?"
                                            class="text-red-500 hover:text-red-700 font-medium transition-colors">
                                        Verwijderen
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-5 py-12 text-center text-gray-400">
                                Geen regels voor dit product.
                                <button wire:click="openCreate" class="ml-1 text-blue-600 hover:underline">Voeg de eerste regel toe.</button>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endif

    {{-- ── Aanmaken / bewerken modal ──────────────────────────────────────── --}}
    @if($showModal)
    <div class="fixed inset-0 z-40 flex items-center justify-center bg-black/50 p-4"
         x-data x-on:keydown.escape.window="$wire.closeModal()">

        <div class="bg-white rounded-xl shadow-xl w-full max-w-lg max-h-[90vh] flex flex-col">

            {{-- Header --}}
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                <h2 class="text-base font-semibold text-gray-800">
                    {{ $editingDependencyId ? 'Regel bewerken' : 'Nieuwe afhankelijkheidsregel' }}
                </h2>
                <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Body --}}
            <form wire:submit="save" class="overflow-y-auto flex-1 px-6 py-5 space-y-5">

                {{-- Regeltype --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Regeltype <span class="text-red-500">*</span>
                    </label>
                    <select wire:model.live="rule_type"
                            class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @foreach($ruleLabels as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    {{-- Contextuele toelichting per regeltype --}}
                    @if($rule_type === 'REQUIRED')
                        <p class="mt-1.5 text-xs text-gray-500">Het afhankelijke product wordt altijd automatisch toegevoegd.
                            <span class="text-gray-400">Voorbeeld: Switch X vereist altijd Netwerkkast.</span></p>
                    @elseif($rule_type === 'REQUIRED_CALCULATED')
                        <p class="mt-1.5 text-xs text-gray-500">Het aantal wordt automatisch berekend op basis van het aantal van het trigger-product.
                            <span class="text-gray-400">Voorbeeld: elke 8 camera's vereist 1 extra PoE switch.</span></p>
                    @elseif($rule_type === 'THRESHOLD_SWITCH')
                        <p class="mt-1.5 text-xs text-gray-500">Boven een bepaald aantal wordt product A vervangen door product B.
                            <span class="text-gray-400">Voorbeeld: bij meer dan 8 camera's wordt de 8-poorts switch vervangen door een 16-poorts switch.</span></p>
                    @elseif($rule_type === 'RECOMMENDED')
                        <p class="mt-1.5 text-xs text-gray-500">Het product wordt voorgesteld maar de verkoper kan het afvinken.
                            <span class="text-gray-400">Voorbeeld: UPS wordt aanbevolen bij een HA Cluster.</span></p>
                    @elseif($rule_type === 'EXCLUDES')
                        <p class="mt-1.5 text-xs text-gray-500">De twee producten kunnen niet samen gekozen worden.
                            <span class="text-gray-400">Voorbeeld: Optie A en Optie B sluiten elkaar uit.</span></p>
                    @endif
                    @error('rule_type') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- Afhankelijk product --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Welk product wordt automatisch toegevoegd? <span class="text-red-500">*</span>
                    </label>
                    <select wire:model="depends_on_product_id"
                            class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">— Kies een product —</option>
                        @foreach($otherProducts->groupBy('category') as $cat => $items)
                            <optgroup label="{{ $cat }}">
                                @foreach($items as $p)
                                    <option value="{{ $p->id }}">{{ $p->name }}</option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                    @if($selectedProductName)
                        <p class="mt-1.5 text-xs text-gray-400">Dit product verschijnt automatisch in de offerte als de verkoper <strong>{{ $selectedProductName }}</strong> selecteert.</p>
                    @endif
                    @error('depends_on_product_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- Minimaal aantal trigger --}}
                @if($showTriggerMin)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Minimaal aantal trigger
                        <span class="text-gray-400 font-normal text-xs">(optioneel)</span>
                    </label>
                    <input wire:model="trigger_quantity_min" type="number" min="1"
                           class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"/>
                    @error('trigger_quantity_min') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                @endif

                {{-- Maximaal aantal trigger --}}
                @if($showTriggerMax)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Maximaal aantal trigger
                        <span class="text-gray-400 font-normal text-xs">(optioneel)</span>
                    </label>
                    <input wire:model="trigger_quantity_max" type="number" min="1"
                           class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"/>
                    @error('trigger_quantity_max') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                @endif

                {{-- Resulterende hoeveelheid --}}
                @if($showResultingQty)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Hoeveel stuks worden toegevoegd?
                        <span class="text-gray-400 font-normal text-xs">(optioneel)</span>
                    </label>
                    <input wire:model="resulting_quantity" type="number" min="1"
                           class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"/>
                    <p class="mt-1.5 text-xs text-gray-400">Standaard 1. Verhoog dit als er altijd meerdere stuks nodig zijn, bijv. 2 SSD-schijven voor RAID 1.</p>
                    @error('resulting_quantity') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                @endif

                {{-- Formule --}}
                @if($showFormula)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Formule
                        <span class="text-gray-400 font-normal text-xs">(bijv. CEIL(trigger/8))</span>
                    </label>
                    <input wire:model="resulting_quantity_formula" type="text"
                           placeholder="CEIL(trigger/8)"
                           class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500 font-mono"/>
                    @error('resulting_quantity_formula') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                @endif

                {{-- Vervangt product --}}
                @if($showReplaces)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Welk product wordt vervangen?
                        <span class="text-gray-400 font-normal text-xs">(optioneel)</span>
                    </label>
                    <select wire:model="replaces_product_id"
                            class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">— Geen —</option>
                        @foreach($otherProducts->groupBy('category') as $cat => $items)
                            <optgroup label="{{ $cat }}">
                                @foreach($items as $p)
                                    <option value="{{ $p->id }}">{{ $p->name }}</option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                    @error('replaces_product_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                @endif

                {{-- Footer --}}
                <div class="flex items-center justify-end gap-3 pt-2 border-t border-gray-100">
                    <button type="button" wire:click="closeModal"
                            class="px-4 py-2 rounded-lg text-sm font-medium text-gray-600 bg-white border border-gray-300 hover:bg-gray-50 transition-colors">
                        Annuleren
                    </button>
                    <button type="submit"
                            class="px-4 py-2 rounded-lg text-sm font-medium text-white shadow-sm transition-colors"
                            style="background-color: #1B3A6B;"
                            wire:loading.attr="disabled">
                        <span wire:loading.remove>Opslaan</span>
                        <span wire:loading>Opslaan…</span>
                    </button>
                </div>

            </form>
        </div>
    </div>
    @endif

    {{-- ── Test modal ─────────────────────────────────────────────────────── --}}
    @if($showTestModal)
    <div class="fixed inset-0 z-40 flex items-center justify-center bg-black/50 p-4"
         x-data x-on:keydown.escape.window="$wire.closeTestModal()">

        <div class="bg-white rounded-xl shadow-xl w-full max-w-lg max-h-[90vh] flex flex-col">

            {{-- Header --}}
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                <h2 class="text-base font-semibold text-gray-800">
                    Regels testen voor {{ $selectedProductName }}
                </h2>
                <button wire:click="closeTestModal" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Body --}}
            <div class="overflow-y-auto flex-1 px-6 py-5">

                {{-- Quantity input --}}
                <div class="mb-5 p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Stel in: een verkoper kiest <strong>{{ $selectedProductName }}</strong> met aantal:
                    </label>
                    <input wire:model.live="testQuantity" type="number" min="1"
                           class="w-28 rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"/>
                </div>

                <p class="text-sm font-semibold text-gray-700 mb-3">Dan gebeurt het volgende:</p>

                @if(count($this->testResults) === 0)
                    <p class="text-sm text-gray-400">Geen regels gevonden voor dit product.</p>
                @else
                    <div class="space-y-2">
                        @foreach($this->testResults as $result)
                            <div class="flex items-start gap-3 p-3 rounded-lg border
                                        {{ $result['applies'] ? 'bg-blue-50 border-blue-100' : 'bg-gray-50 border-gray-100' }}">
                                <div class="flex-shrink-0 mt-0.5">
                                    @if($result['applies'])
                                        <svg class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                        </svg>
                                    @endif
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-xs font-semibold {{ $result['applies'] ? 'text-blue-700' : 'text-gray-400' }}">
                                        {{ $result['label'] }}
                                    </p>
                                    <p class="text-sm mt-0.5 {{ $result['applies'] ? 'text-gray-800' : 'text-gray-400' }}">
                                        {{ $result['description'] }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="px-6 py-4 border-t border-gray-100 flex justify-end">
                <button wire:click="closeTestModal"
                        class="px-4 py-2 rounded-lg text-sm font-medium text-gray-600 bg-white border border-gray-300 hover:bg-gray-50 transition-colors">
                    Sluiten
                </button>
            </div>
        </div>
    </div>
    @endif

</div>
