<div
    x-data="{ notifyMessage: null }"
    x-on:notify.window="notifyMessage = $event.detail.message; setTimeout(() => notifyMessage = null, 3500)"
>
    <x-breadcrumb :items="[['label' => 'Beheer', 'route' => 'beheer.dashboard'], ['label' => 'Afhankelijkheden']]"/>

    {{-- Inline success toast (dispatched from component) --}}
    <div
        x-show="notifyMessage"
        x-transition.opacity
        class="fixed top-5 right-5 z-50 flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 rounded-lg px-4 py-3 text-sm shadow-md"
        style="display:none"
    >
        <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
        <span x-text="notifyMessage"></span>
    </div>

    {{-- Product selector --}}
    <div class="flex items-center justify-between mb-5">
        <div class="flex items-center gap-3">
            <label class="text-sm font-medium text-gray-700">Product:</label>
            <select wire:model.live="selectedProductId"
                    class="rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500 min-w-72">
                <option value="">— Kies een product —</option>
                @foreach($products->groupBy('category') as $cat => $items)
                    <optgroup label="{{ $cat }}">
                        @foreach($items as $p)
                            <option value="{{ $p->id }}">{{ $p->name }}</option>
                        @endforeach
                    </optgroup>
                @endforeach
            </select>
        </div>

        @if($selectedProductId)
            <button wire:click="openCreate"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium text-white shadow-sm transition-colors"
                    style="background-color: #1B3A6B;">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Nieuwe regel
            </button>
        @endif
    </div>

    {{-- Table or placeholder --}}
    @if(! $selectedProductId)
        <div class="bg-white rounded-xl border border-gray-200 p-16 text-center text-gray-400">
            <svg class="w-8 h-8 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            Kies een product om de afhankelijkheidsregels te bekijken.
        </div>
    @else
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200 text-left">
                        <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Regeltype</th>
                        <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Afhankelijk product</th>
                        <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Drempelwaarden</th>
                        <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Hoeveelheid / formule</th>
                        <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Vervangt</th>
                        <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide text-right">Acties</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($dependencies as $dep)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-5 py-3.5">
                                <span @class([
                                    'inline-flex items-center px-2 py-0.5 rounded text-xs font-medium',
                                    'bg-red-100 text-red-700'    => $dep->rule_type === 'EXCLUDES',
                                    'bg-blue-100 text-blue-700'  => $dep->rule_type === 'REQUIRED',
                                    'bg-purple-100 text-purple-700' => $dep->rule_type === 'REQUIRED_CALCULATED',
                                    'bg-amber-100 text-amber-700'   => $dep->rule_type === 'THRESHOLD_SWITCH',
                                    'bg-green-100 text-green-700'   => $dep->rule_type === 'RECOMMENDED',
                                ])>
                                    {{ $ruleLabels[$dep->rule_type] ?? $dep->rule_type }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-gray-800 font-medium">
                                {{ $dep->dependsOnProduct->name ?? '—' }}
                            </td>
                            <td class="px-5 py-3.5 text-gray-500">
                                @if($dep->trigger_quantity_min !== null || $dep->trigger_quantity_max !== null)
                                    {{ $dep->trigger_quantity_min ?? '?' }} – {{ $dep->trigger_quantity_max ?? '∞' }}
                                @else
                                    —
                                @endif
                            </td>
                            <td class="px-5 py-3.5 text-gray-500">
                                @if($dep->resulting_quantity_formula)
                                    <code class="text-xs bg-gray-100 px-1.5 py-0.5 rounded">{{ $dep->resulting_quantity_formula }}</code>
                                @elseif($dep->resulting_quantity !== null)
                                    {{ $dep->resulting_quantity }}
                                @else
                                    —
                                @endif
                            </td>
                            <td class="px-5 py-3.5 text-gray-500">
                                {{ $dep->replacesProduct?->name ?? '—' }}
                            </td>
                            <td class="px-5 py-3.5 text-right">
                                <div class="flex items-center justify-end gap-4">
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
                            <td colspan="6" class="px-5 py-12 text-center text-gray-400">
                                Geen regels gevonden voor dit product.
                                <button wire:click="openCreate" class="ml-1 text-blue-600 hover:underline">Voeg de eerste regel toe.</button>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endif

    {{-- ── Modal ─────────────────────────────────────────────────────────── --}}
    @if($showModal)
    <div class="fixed inset-0 z-40 flex items-center justify-center bg-black/50 p-4"
         x-data x-on:keydown.escape.window="$wire.closeModal()">

        <div class="bg-white rounded-xl shadow-xl w-full max-w-lg max-h-[90vh] flex flex-col">

            {{-- Modal header --}}
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                <h2 class="text-base font-semibold text-gray-800">
                    {{ $editingDependencyId ? 'Regel bewerken' : 'Nieuwe afhankelijkheidsregel' }}
                </h2>
                <button wire:click="closeModal"
                        class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Modal body --}}
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
                    @error('rule_type') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- Afhankelijk product --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Afhankelijk product <span class="text-red-500">*</span>
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
                        Resulterende hoeveelheid
                        <span class="text-gray-400 font-normal text-xs">(optioneel)</span>
                    </label>
                    <input wire:model="resulting_quantity" type="number" min="1"
                           class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"/>
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
                        Vervangt product
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

                {{-- Modal footer --}}
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

</div>
