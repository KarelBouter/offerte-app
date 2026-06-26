@if($isOpen)
<div class="fixed inset-0 z-50 flex items-center justify-center p-4"
     x-data x-on:keydown.escape.window="$wire.close()">

    {{-- Overlay --}}
    <div class="absolute inset-0 bg-black/40" wire:click="close"></div>

    {{-- Modal --}}
    <div class="relative bg-white rounded-xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto"
         @click.stop>

        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
            <h2 class="text-base font-semibold text-gray-800">
                {{ $taskId ? 'Taak bewerken' : 'Nieuwe taak' }}
            </h2>
            <button wire:click="close" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form wire:submit="save" class="px-6 py-5 space-y-4">

            {{-- Taaknaam --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Taaknaam <span class="text-red-500">*</span>
                </label>
                <input wire:model="title" type="text" placeholder="Bijv. Korting bespreken met klant"
                       class="w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500"/>
                @error('title') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Omschrijving met @-mention autocomplete --}}
            <div x-data="{
                content: @entangle('description').live,
                mentionOpen: false,
                mentionSearch: '',
                mentionStart: -1,
                allUsers: {{ json_encode(collect($users)->map(fn($u) => ['id' => $u['id'], 'name' => $u['name'], 'first' => explode(' ', $u['name'])[0]])) }},
                get filteredUsers() {
                    if (!this.mentionSearch) return this.allUsers.slice(0, 6);
                    const s = this.mentionSearch.toLowerCase();
                    return this.allUsers.filter(u => u.name.toLowerCase().includes(s)).slice(0, 6);
                },
                onInput(e) {
                    const pos = e.target.selectionStart;
                    const text = e.target.value;
                    const lastAt = text.lastIndexOf('@', pos - 1);
                    if (lastAt !== -1) {
                        const between = text.substring(lastAt + 1, pos);
                        if (!between.includes(' ') && !between.includes('\n')) {
                            this.mentionOpen = true;
                            this.mentionSearch = between;
                            this.mentionStart = lastAt;
                            return;
                        }
                    }
                    this.mentionOpen = false;
                },
                selectUser(user) {
                    const el = this.$refs.textarea;
                    const before = this.content.substring(0, this.mentionStart);
                    const after = this.content.substring(el.selectionStart);
                    this.content = before + '@' + user.first + ' ' + after;
                    this.mentionOpen = false;
                    this.mentionSearch = '';
                    this.$nextTick(() => el.focus());
                }
            }">
                <label class="block text-sm font-medium text-gray-700 mb-1">Omschrijving / notitie</label>
                <div class="relative">
                    <textarea x-ref="textarea"
                              x-model="content"
                              @input="onInput"
                              @keydown.escape="mentionOpen = false"
                              rows="4"
                              placeholder="Typ @naam om iemand te taggen…"
                              class="w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500 resize-y"></textarea>

                    {{-- Autocomplete dropdown --}}
                    <div x-show="mentionOpen && filteredUsers.length > 0"
                         x-transition
                         class="absolute z-10 left-0 right-0 bg-white border border-gray-200 rounded-lg shadow-lg mt-1 overflow-hidden">
                        <template x-for="user in filteredUsers" :key="user.id">
                            <button type="button"
                                    @click="selectUser(user)"
                                    class="w-full text-left px-3 py-2 text-sm hover:bg-blue-50 flex items-center gap-2">
                                <span class="w-6 h-6 rounded-full bg-blue-100 text-blue-700 text-xs font-bold flex items-center justify-center flex-shrink-0"
                                      x-text="user.name.charAt(0)"></span>
                                <span x-text="user.name" class="font-medium text-gray-800"></span>
                            </button>
                        </template>
                    </div>
                </div>
                @error('description') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Toewijzen aan --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Toewijzen aan</label>
                <select wire:model="assignedToUserId"
                        class="w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">— Niet toegewezen —</option>
                    @foreach($users as $user)
                        <option value="{{ $user['id'] }}">{{ $user['name'] }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Koppelen aan offerte --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Koppelen aan offerte</label>
                @if($selectedQuoteLabel)
                    <div class="flex items-center gap-2">
                        <span class="flex-1 px-3 py-2 bg-blue-50 border border-blue-200 rounded-lg text-sm text-blue-800 font-medium">
                            {{ $selectedQuoteLabel }}
                        </span>
                        <button type="button" wire:click="clearQuote"
                                class="text-gray-400 hover:text-red-500 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                @else
                    <div class="relative">
                        <input wire:model.live.debounce.300ms="quoteSearch"
                               type="text"
                               placeholder="Zoek op offertenummer of klantnaam…"
                               class="w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500"/>
                        @if(count($quoteSuggestions) > 0)
                            <div class="absolute z-10 left-0 right-0 bg-white border border-gray-200 rounded-lg shadow-lg mt-1 overflow-hidden">
                                @foreach($quoteSuggestions as $s)
                                    <button type="button"
                                            wire:click="selectQuote({{ $s['id'] }}, '{{ addslashes($s['label']) }}')"
                                            class="w-full text-left px-3 py-2 text-sm hover:bg-blue-50 text-gray-800">
                                        {{ $s['label'] }}
                                    </button>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            {{-- Deadline --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Deadline</label>
                <input wire:model="dueDate" type="date"
                       class="w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500"/>
            </div>

            {{-- Status (alleen bij bewerken) --}}
            @if($taskId)
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select wire:model="status"
                        class="w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="open">Open</option>
                    <option value="in_behandeling">In behandeling</option>
                    <option value="afgerond">Afgerond</option>
                </select>
            </div>
            @endif

            {{-- Acties --}}
            <div class="flex items-center justify-end gap-3 pt-2">
                <button type="button" wire:click="close"
                        class="px-4 py-2 rounded-lg text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 transition-colors">
                    Annuleren
                </button>
                <button type="submit"
                        class="px-5 py-2 rounded-lg text-sm font-medium text-white transition-colors"
                        style="background-color: #1B3A6B;"
                        wire:loading.attr="disabled" wire:loading.class="opacity-70">
                    <span wire:loading.remove>{{ $taskId ? 'Opslaan' : 'Taak aanmaken' }}</span>
                    <span wire:loading>Opslaan…</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endif
