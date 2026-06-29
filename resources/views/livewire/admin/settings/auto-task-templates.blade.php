<div x-data="{ notifyMessage: null }"
     x-on:notify.window="notifyMessage = $event.detail.message; setTimeout(() => notifyMessage = null, 3000)">

    {{-- Succes toast --}}
    <div x-show="notifyMessage"
         x-transition
         class="mb-4 flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 text-sm px-4 py-3 rounded-lg">
        <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
        </svg>
        <span x-text="notifyMessage"></span>
    </div>

    {{-- Info block --}}
    <div class="mb-5 flex gap-3 rounded-lg border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-800">
        <svg class="mt-0.5 h-4 w-4 flex-shrink-0 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
        </svg>
        <div>
            Automatische taken worden aangemaakt zodra een offerte de opgegeven status bereikt. Gebruik de variabelen
            <code class="rounded bg-blue-100 px-1 font-mono text-xs">@{{klant}}</code>,
            <code class="rounded bg-blue-100 px-1 font-mono text-xs">@{{offerte_nr}}</code>,
            <code class="rounded bg-blue-100 px-1 font-mono text-xs">@{{bedrag_eenmalig}}</code>,
            <code class="rounded bg-blue-100 px-1 font-mono text-xs">@{{bedrag_jaarlijks}}</code> en
            <code class="rounded bg-blue-100 px-1 font-mono text-xs">@{{verkoper}}</code>
            in de taaknaam of omschrijving.
        </div>
    </div>

    {{-- Header + knop --}}
    <div class="mb-4 flex items-center justify-between">
        <h3 class="text-sm font-semibold text-gray-700">Taaktemplates ({{ $templates->count() }})</h3>
        <button wire:click="openCreate"
                class="inline-flex items-center gap-1.5 rounded-lg px-3 py-2 text-sm font-medium text-white shadow-sm"
                style="background-color: #1B3A6B;">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Nieuw template
        </button>
    </div>

    {{-- Tabel --}}
    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
        @if($templates->isEmpty())
            <div class="px-6 py-10 text-center text-sm text-gray-400">
                Nog geen taaktemplates aangemaakt. Klik op "Nieuw template" om te beginnen.
            </div>
        @else
            <div class="overflow-x-auto"><table class="w-full text-sm min-w-[600px]">
                <thead>
                    <tr class="border-b border-gray-200 bg-gray-50">
                        <th class="px-4 py-3 text-left font-medium text-gray-600">Naam</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-600">Trigger</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-600">Taaknaam</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-600">Vervaldatum</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-600">Actief</th>
                        <th class="px-4 py-3 text-right font-medium text-gray-600">Acties</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($templates as $t)
                    <tr class="transition-colors hover:bg-gray-50 {{ !$t->is_active ? 'opacity-50' : '' }}">
                        <td class="px-4 py-3 font-medium text-gray-800">{{ $t->name }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $triggerColors[$t->trigger_status] ?? 'bg-gray-100 text-gray-600' }}">
                                {{ $triggerLabels[$t->trigger_status] ?? $t->trigger_status }}
                            </span>
                        </td>
                        <td class="max-w-xs px-4 py-3 text-gray-600 truncate">{{ $t->title_template }}</td>
                        <td class="px-4 py-3 text-gray-500">
                            {{ $t->due_days !== null ? '+' . $t->due_days . ' dag' . ($t->due_days !== 1 ? 'en' : '') : '—' }}
                        </td>
                        <td class="px-4 py-3">
                            <button wire:click="toggleActive({{ $t->id }})"
                                    class="relative inline-flex h-5 w-9 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none {{ $t->is_active ? 'bg-blue-600' : 'bg-gray-200' }}">
                                <span class="inline-block h-4 w-4 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ $t->is_active ? 'translate-x-4' : 'translate-x-0' }}"></span>
                            </button>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-end gap-1">
                                <button wire:click="moveUp({{ $t->id }})"
                                        title="Omhoog"
                                        class="rounded p-1 text-gray-400 hover:bg-gray-100 hover:text-gray-600">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                                    </svg>
                                </button>
                                <button wire:click="moveDown({{ $t->id }})"
                                        title="Omlaag"
                                        class="rounded p-1 text-gray-400 hover:bg-gray-100 hover:text-gray-600">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                                <button wire:click="openEdit({{ $t->id }})"
                                        class="rounded px-2.5 py-1 text-xs font-medium text-blue-700 hover:bg-blue-50">
                                    Bewerken
                                </button>
                                <button wire:click="delete({{ $t->id }})"
                                        wire:confirm="Template '{{ $t->name }}' verwijderen?"
                                        class="rounded px-2.5 py-1 text-xs font-medium text-red-600 hover:bg-red-50">
                                    Verwijderen
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table></div>
        @endif
    </div>

    {{-- Modal --}}
    @if($showModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4"
         x-data x-on:keydown.escape.window="$wire.closeModal()">
        <div class="w-full max-w-xl rounded-2xl bg-white shadow-2xl" @click.stop>

            <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4">
                <h2 class="text-base font-semibold text-gray-800">
                    {{ $editingId ? 'Template bewerken' : 'Nieuw taaktemplate' }}
                </h2>
                <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div class="space-y-4 px-6 py-5">

                {{-- Naam --}}
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">
                        Naam <span class="text-red-500">*</span>
                    </label>
                    <input wire:model="name" type="text" placeholder="Bijv. Factuur versturen"
                           class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"/>
                    @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- Trigger + vervaldatum --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">
                            Trigger op status <span class="text-red-500">*</span>
                        </label>
                        <select wire:model="trigger_status"
                                class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @foreach($triggerLabels as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('trigger_status') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">Vervaldatum na trigger</label>
                        <div class="flex items-center gap-2">
                            <input wire:model="due_days" type="number" min="0" max="365" placeholder="—"
                                   class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"/>
                            <span class="whitespace-nowrap text-sm text-gray-500">dagen</span>
                        </div>
                        @error('due_days') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Taaknaam --}}
                <div x-data="{ field: 'title_template' }">
                    <label class="mb-1 block text-sm font-medium text-gray-700">
                        Taaknaam <span class="text-red-500">*</span>
                    </label>
                    <input wire:model="title_template" id="title-template-input" type="text"
                           placeholder="Bijv. Verstuur factuur voor @{{klant}}"
                           class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"/>
                    @error('title_template') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    {{-- Variable badges --}}
                    <div class="mt-1.5 flex flex-wrap gap-1.5">
                        @foreach($vars as $varKey => $varLabel)
                        <button type="button"
                                x-on:click="
                                    let el = document.getElementById('title-template-input');
                                    let start = el.selectionStart, end = el.selectionEnd;
                                    let val = el.value;
                                    el.value = val.slice(0, start) + '{{ $varKey }}' + val.slice(end);
                                    $wire.set('title_template', el.value);
                                    el.focus();
                                    el.setSelectionRange(start + {{ strlen($varKey) }}, start + {{ strlen($varKey) }});
                                "
                                class="inline-flex items-center rounded border border-gray-200 bg-gray-50 px-1.5 py-0.5 font-mono text-xs text-gray-600 hover:bg-blue-50 hover:border-blue-300 hover:text-blue-700 cursor-pointer">
                            {{ $varKey }}
                        </button>
                        @endforeach
                    </div>
                </div>

                {{-- Omschrijving --}}
                <div x-data>
                    <label class="mb-1 block text-sm font-medium text-gray-700">Omschrijving</label>
                    <textarea wire:model="description_template" id="desc-template-input" rows="3"
                              placeholder="Optionele omschrijving voor de taak…"
                              class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                    @error('description_template') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    {{-- Variable badges --}}
                    <div class="mt-1.5 flex flex-wrap gap-1.5">
                        @foreach($vars as $varKey => $varLabel)
                        <button type="button"
                                x-on:click="
                                    let el = document.getElementById('desc-template-input');
                                    let start = el.selectionStart, end = el.selectionEnd;
                                    let val = el.value;
                                    el.value = val.slice(0, start) + '{{ $varKey }}' + val.slice(end);
                                    $wire.set('description_template', el.value);
                                    el.focus();
                                    el.setSelectionRange(start + {{ strlen($varKey) }}, start + {{ strlen($varKey) }});
                                "
                                class="inline-flex items-center rounded border border-gray-200 bg-gray-50 px-1.5 py-0.5 font-mono text-xs text-gray-600 hover:bg-blue-50 hover:border-blue-300 hover:text-blue-700 cursor-pointer">
                            {{ $varKey }}
                        </button>
                        @endforeach
                    </div>
                </div>

                {{-- Toewijzen aan --}}
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">Toewijzen aan</label>
                    <select wire:model="assign_to_user_id"
                            class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Niet toegewezen</option>
                        @foreach($users as $u)
                            <option value="{{ $u->id }}">{{ $u->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Actief --}}
                <div class="flex items-center gap-3">
                    <button wire:click="$toggle('is_active')"
                            class="relative inline-flex h-5 w-9 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none {{ $is_active ? 'bg-blue-600' : 'bg-gray-200' }}">
                        <span class="inline-block h-4 w-4 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ $is_active ? 'translate-x-4' : 'translate-x-0' }}"></span>
                    </button>
                    <span class="text-sm text-gray-700">Template actief</span>
                </div>
            </div>

            <div class="flex justify-end gap-3 border-t border-gray-200 px-6 py-4">
                <button wire:click="closeModal"
                        class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                    Annuleren
                </button>
                <button wire:click="save"
                        wire:loading.attr="disabled"
                        class="rounded-lg px-4 py-2 text-sm font-medium text-white shadow-sm"
                        style="background-color: #1B3A6B;">
                    <span wire:loading.remove>{{ $editingId ? 'Opslaan' : 'Aanmaken' }}</span>
                    <span wire:loading>Opslaan…</span>
                </button>
            </div>

        </div>
    </div>
    @endif
</div>
