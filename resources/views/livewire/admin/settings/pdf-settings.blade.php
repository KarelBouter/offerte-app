<div>
    @if(session('pdf_success'))
    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)"
         x-show="show" x-transition
         class="mb-5 flex items-center gap-3 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
        <svg class="h-4 w-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
        </svg>
        {{ session('pdf_success') }}
    </div>
    @endif

    {{-- ── Opmaak ─────────────────────────────────────────────────────── --}}
    <div class="mb-5 rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
        <h3 class="mb-5 text-sm font-semibold uppercase tracking-wide text-gray-700">Opmaak</h3>

        <div class="space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">Primaire kleur</label>
                    <div class="flex items-center gap-2">
                        <input wire:model.live="pdf_primary_color" type="color"
                               class="h-9 w-10 cursor-pointer rounded border border-gray-300 p-0.5"/>
                        <input wire:model.live="pdf_primary_color" type="text"
                               class="w-full rounded-lg border-gray-300 font-mono text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
                               placeholder="#1B3A6B"/>
                        <button wire:click="resetField('pdf_primary_color')" type="button" title="Standaard herstellen"
                                class="flex-shrink-0 rounded p-1.5 text-gray-400 hover:bg-gray-100 hover:text-gray-600">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                        </button>
                    </div>
                    @error('pdf_primary_color') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    <div class="mt-2 h-5 w-full rounded" style="background-color: {{ $pdf_primary_color }};"></div>
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">Lettertype</label>
                    <div class="flex items-center gap-2">
                        <select wire:model="pdf_font_family"
                                class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="DejaVu Sans">DejaVu Sans</option>
                            <option value="DejaVu Serif">DejaVu Serif</option>
                            <option value="Helvetica">Helvetica</option>
                            <option value="Times New Roman">Times New Roman</option>
                        </select>
                        <button wire:click="resetField('pdf_font_family')" type="button" title="Standaard herstellen"
                                class="flex-shrink-0 rounded p-1.5 text-gray-400 hover:bg-gray-100 hover:text-gray-600">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">Lettergrootte tekst (pt)</label>
                    <div class="flex items-center gap-2">
                        <input wire:model="pdf_font_size_body" type="number" min="6" max="20" step="0.5"
                               class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"/>
                        <button wire:click="resetField('pdf_font_size_body')" type="button" title="Standaard herstellen"
                                class="flex-shrink-0 rounded p-1.5 text-gray-400 hover:bg-gray-100 hover:text-gray-600">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                        </button>
                    </div>
                    @error('pdf_font_size_body') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">Lettergrootte kopjes (pt)</label>
                    <div class="flex items-center gap-2">
                        <input wire:model="pdf_font_size_heading" type="number" min="6" max="24" step="0.5"
                               class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"/>
                        <button wire:click="resetField('pdf_font_size_heading')" type="button" title="Standaard herstellen"
                                class="flex-shrink-0 rounded p-1.5 text-gray-400 hover:bg-gray-100 hover:text-gray-600">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                        </button>
                    </div>
                    @error('pdf_font_size_heading') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">Paginamarge (mm)</label>
                    <div class="flex items-center gap-2">
                        <input wire:model="pdf_margin_mm" type="number" min="5" max="50"
                               class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"/>
                        <button wire:click="resetField('pdf_margin_mm')" type="button" title="Standaard herstellen"
                                class="flex-shrink-0 rounded p-1.5 text-gray-400 hover:bg-gray-100 hover:text-gray-600">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                        </button>
                    </div>
                    @error('pdf_margin_mm') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700">Voettekst PDF</label>
                <div class="flex items-center gap-2">
                    <input wire:model="pdf_footer_text" type="text"
                           class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
                           placeholder="@{{company_name}} — pagina"/>
                    <button wire:click="resetField('pdf_footer_text')" type="button" title="Standaard herstellen"
                            class="flex-shrink-0 rounded p-1.5 text-gray-400 hover:bg-gray-100 hover:text-gray-600">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                    </button>
                </div>
                <p class="mt-1 text-xs text-gray-400">Gebruik <code class="rounded bg-gray-100 px-1">@{{company_name}}</code> voor de bedrijfsnaam.</p>
            </div>
        </div>
    </div>

    {{-- ── Artikelteksten ──────────────────────────────────────────────── --}}
    <div class="mb-5 rounded-xl border border-gray-200 bg-white shadow-sm">
        <div class="border-b border-gray-200 px-6 py-4">
            <h3 class="text-sm font-semibold uppercase tracking-wide text-gray-700">Artikelteksten</h3>
            <p class="mt-0.5 text-xs text-gray-400">Klik op een artikel om de tekst te bewerken. De reset-knop herstelt de originele standaardtekst.</p>
        </div>

        @php
        $articleSections = [
            ['key' => 'art2', 'label' => 'Artikel 2 — Omschrijving van de dienst', 'fields' => [
                ['prop' => 'pdf_tekst_artikel_2', 'label' => 'Hoofdtekst'],
                ['prop' => 'pdf_tekst_artikel_2_afbakening', 'label' => 'Afbakening'],
            ]],
            ['key' => 'art3', 'label' => 'Artikel 3 — Toepasselijke voorwaarden', 'fields' => [
                ['prop' => 'pdf_tekst_artikel_3', 'label' => 'Alinea 1'],
                ['prop' => 'pdf_tekst_artikel_3_2', 'label' => 'Alinea 2'],
            ]],
            ['key' => 'art6', 'label' => 'Artikel 6 — Servicecontract afbakening', 'fields' => [
                ['prop' => 'pdf_tekst_artikel_6_afbakening', 'label' => 'Afbakening'],
                ['prop' => 'pdf_tekst_artikel_6_2', 'label' => 'Hardware vervanging'],
            ]],
            ['key' => 'art7', 'label' => 'Artikel 7 — Betaalvoorwaarden', 'fields' => [
                ['prop' => 'pdf_tekst_artikel_7', 'label' => 'Voetnoot tabel'],
            ]],
            ['key' => 'art8', 'label' => 'Artikel 8 — Looptijd en opzegging', 'fields' => [
                ['prop' => 'pdf_tekst_artikel_8', 'label' => 'Alinea 1'],
                ['prop' => 'pdf_tekst_artikel_8_2', 'label' => 'Alinea 2'],
            ]],
            ['key' => 'art9', 'label' => 'Artikel 9 — Specifieke afspraken', 'fields' => [
                ['prop' => 'pdf_tekst_artikel_9_1', 'label' => '9.1 Eigendom hardware'],
                ['prop' => 'pdf_tekst_artikel_9_2', 'label' => '9.2 Toegang locatie'],
                ['prop' => 'pdf_tekst_artikel_9_3', 'label' => '9.3 Wijzigingen infrastructuur'],
                ['prop' => 'pdf_tekst_artikel_9_4', 'label' => '9.4 Geheimhouding'],
                ['prop' => 'pdf_tekst_artikel_9_5', 'label' => '9.5 Overmacht'],
                ['prop' => 'pdf_tekst_artikel_9_6', 'label' => '9.6 Toepasselijk recht'],
                ['prop' => 'pdf_tekst_artikel_9_7', 'label' => '9.7 Wijziging en aanvulling'],
            ]],
            ['key' => 'art10', 'label' => 'Artikel 10 — Samenvatting voetnoot', 'fields' => [
                ['prop' => 'pdf_tekst_artikel_10_footer', 'label' => 'Voetnoot prijstabel'],
            ]],
        ];
        @endphp

        <div class="divide-y divide-gray-100">
            @foreach($articleSections as $section)
            <div x-data="{ open: false }">
                <button type="button"
                        x-on:click="open = !open"
                        class="flex w-full items-center justify-between px-6 py-3.5 text-left transition-colors hover:bg-gray-50">
                    <span class="text-sm font-medium text-gray-800">{{ $section['label'] }}</span>
                    <svg class="h-4 w-4 flex-shrink-0 text-gray-400 transition-transform"
                         x-bind:class="open ? 'rotate-180' : ''"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="open" x-transition class="border-t border-gray-100 space-y-4 px-6 py-5">
                    @foreach($section['fields'] as $field)
                    <div>
                        <div class="mb-1.5 flex items-center justify-between">
                            <label class="text-sm font-medium text-gray-700">{{ $field['label'] }}</label>
                            <button wire:click="resetField('{{ $field['prop'] }}')" type="button"
                                    class="inline-flex items-center gap-1 rounded px-2 py-0.5 text-xs text-gray-500 hover:bg-gray-100 hover:text-gray-700">
                                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                                Standaard
                            </button>
                        </div>
                        <textarea wire:model="{{ $field['prop'] }}" rows="4"
                                  class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                        @error($field['prop']) <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Opslaan --}}
    <div class="flex justify-end">
        <button wire:click="save" type="button"
                wire:loading.attr="disabled"
                class="rounded-lg px-5 py-2.5 text-sm font-medium text-white shadow-sm"
                style="background-color: #1B3A6B;">
            <span wire:loading.remove>PDF-instellingen opslaan</span>
            <span wire:loading>Opslaan…</span>
        </button>
    </div>
</div>
