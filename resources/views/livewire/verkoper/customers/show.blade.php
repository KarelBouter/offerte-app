<div class="space-y-6">

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 rounded-lg px-4 py-3 text-sm">
            {{ session('success') }}
        </div>
    @endif

    {{-- Klantgegevens --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-gray-800">Klantgegevens</h2>
            @if(!$editingInfo)
                <button wire:click="startEditing"
                    class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                    Bewerken
                </button>
            @endif
        </div>

        @if($editingInfo)
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Bedrijfsnaam</label>
                    <input wire:model="companyName" type="text" class="w-full border rounded-lg px-3 py-2 text-sm">
                    @error('companyName') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">KvK-nummer</label>
                    <input wire:model="kvkNumber" type="text" class="w-full border rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Adres</label>
                    <input wire:model="address" type="text" class="w-full border rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Website</label>
                    <input wire:model="website" type="text" class="w-full border rounded-lg px-3 py-2 text-sm" placeholder="https://...">
                    @error('website') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Contactpersoon</label>
                    <input wire:model="contactName" type="text" class="w-full border rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">E-mail</label>
                    <input wire:model="contactEmail" type="email" class="w-full border rounded-lg px-3 py-2 text-sm">
                    @error('contactEmail') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Telefoon</label>
                    <input wire:model="contactPhone" type="text" class="w-full border rounded-lg px-3 py-2 text-sm">
                </div>
            </div>
            <div class="flex gap-3 mt-4">
                <button wire:click="saveInfo"
                    class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700">
                    Opslaan
                </button>
                <button wire:click="cancelEditing"
                    class="px-4 py-2 border border-gray-300 text-gray-600 text-sm rounded-lg hover:bg-gray-50">
                    Annuleren
                </button>
            </div>
        @else
            <div class="grid grid-cols-2 gap-y-3 text-sm">
                <div><span class="text-gray-500">Bedrijfsnaam:</span> <span class="font-medium">{{ $customer->company_name }}</span></div>
                <div><span class="text-gray-500">KvK-nummer:</span> {{ $customer->kvk_number }}</div>
                <div><span class="text-gray-500">Adres:</span> {{ $customer->address }}</div>
                <div><span class="text-gray-500">Website:</span>
                    @if($customer->website)
                        <a href="{{ $customer->website }}" target="_blank" class="text-blue-600 hover:underline">{{ $customer->website }}</a>
                    @else
                        <span class="text-gray-400">—</span>
                    @endif
                </div>
                <div><span class="text-gray-500">Contactpersoon:</span> {{ $customer->contact_name }}</div>
                <div><span class="text-gray-500">E-mail:</span> {{ $customer->contact_email }}</div>
                <div><span class="text-gray-500">Telefoon:</span> {{ $customer->contact_phone ?? '—' }}</div>
            </div>
        @endif
    </div>

    {{-- Actieve configuratie --}}
    @if($activeConfig)
    <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
        <h2 class="text-lg font-semibold text-blue-800 mb-3">Actieve configuratie</h2>
        <p class="text-sm text-blue-700 mb-3">
            Offerte <b>{{ $activeConfig->quote_number }}</b> —
            ondertekend op {{ $activeConfig->signed_at?->format('d-m-Y') ?? '—' }}
        </p>
        <div class="space-y-1">
            @foreach($activeConfig->items as $item)
                @if($item->product)
                <div class="text-sm text-blue-800">
                    {{ $item->product->name }}
                    @if($item->quantity > 1) × {{ $item->quantity }} @endif
                </div>
                @endif
            @endforeach
        </div>
        <div class="mt-3 text-sm text-blue-700 font-medium">
            Eenmalig: €{{ number_format($activeConfig->total_onetime_excl_vat, 2, ',', '.') }} excl. BTW
            &nbsp;·&nbsp;
            Jaarlijks: €{{ number_format($activeConfig->total_yearly_excl_vat, 2, ',', '.') }} excl. BTW
        </div>
    </div>
    @endif

    {{-- Offertehistorie --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Offertehistorie</h2>
        @if($customer->quotes->isEmpty())
            <p class="text-sm text-gray-400">Nog geen offertes.</p>
        @else
            <div class="space-y-2">
                @foreach($customer->quotes as $quote)
                <div class="flex items-center justify-between py-2 border-b border-gray-100 last:border-0">
                    <div>
                        <span class="text-sm font-medium text-gray-800">{{ $quote->quote_number }}</span>
                        <span class="ml-2 text-xs px-2 py-0.5 rounded-full
                            {{ $quote->status === 'ondertekend' ? 'bg-green-100 text-green-700' :
                               ($quote->status === 'verzonden' ? 'bg-blue-100 text-blue-700' :
                               ($quote->status === 'verlopen' ? 'bg-orange-100 text-orange-700' :
                               ($quote->status === 'geannuleerd' ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-600'))) }}">
                            {{ ucfirst($quote->status) }}
                        </span>
                        <span class="ml-2 text-xs text-gray-400">v{{ $quote->revision }}</span>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="text-xs text-gray-500">
                            €{{ number_format($quote->total_onetime_excl_vat, 2, ',', '.') }}
                            + €{{ number_format($quote->total_yearly_excl_vat, 2, ',', '.') }}/jr
                        </span>
                        <a href="{{ route('verkoper.offertes.show', $quote) }}"
                           class="text-xs text-blue-600 hover:text-blue-800 font-medium">
                            Bekijken →
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Taken --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-gray-800">Taken</h2>
            <button wire:click="$set('showTaskForm', true)"
                class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                + Taak toevoegen
            </button>
        </div>

        @if($showTaskForm)
        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-4">
            <div class="space-y-3">
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Omschrijving *</label>
                    <input wire:model="taskTitle" type="text" class="w-full border rounded-lg px-3 py-2 text-sm"
                           placeholder="Wat moet er gebeuren?">
                    @error('taskTitle') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Toelichting</label>
                    <textarea wire:model="taskDescription" rows="2"
                        class="w-full border rounded-lg px-3 py-2 text-sm"></textarea>
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Deadline</label>
                    <input wire:model="taskDueDate" type="date" class="border rounded-lg px-3 py-2 text-sm">
                </div>
                <div class="flex gap-2">
                    <button wire:click="saveTask"
                        class="px-3 py-1.5 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700">
                        Opslaan
                    </button>
                    <button wire:click="$set('showTaskForm', false)"
                        class="px-3 py-1.5 border border-gray-300 text-gray-600 text-sm rounded-lg">
                        Annuleren
                    </button>
                </div>
            </div>
        </div>
        @endif

        @if($customer->tasks->isEmpty())
            <p class="text-sm text-gray-400">Geen taken.</p>
        @else
            <div class="space-y-2">
                @foreach($customer->tasks as $task)
                <div class="flex items-start justify-between py-2 border-b border-gray-100 last:border-0">
                    <div>
                        <span class="text-sm font-medium text-gray-800">{{ $task->title }}</span>
                        @if($task->due_date)
                            <span class="ml-2 text-xs text-gray-400">
                                Deadline: {{ $task->due_date->format('d-m-Y') }}
                            </span>
                        @endif
                        <div class="text-xs text-gray-500 mt-0.5">
                            {{ $task->assignedTo?->name ?? 'Niet toegewezen' }}
                            &nbsp;·&nbsp;
                            <span class="{{ $task->status === 'afgerond' ? 'text-green-600' : 'text-gray-500' }}">
                                {{ ucfirst($task->status) }}
                            </span>
                        </div>
                    </div>
                    <a href="{{ route('taken.show', $task) }}"
                       class="text-xs text-blue-600 hover:text-blue-800 font-medium ml-4 shrink-0">
                        Bekijken →
                    </a>
                </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Notities --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Notities</h2>

        <div class="mb-4">
            <textarea
                wire:model="newNote"
                rows="3"
                placeholder="Voeg een notitie toe..."
                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
            ></textarea>
            @error('newNote') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            <button wire:click="addNote"
                class="mt-2 px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700">
                Notitie toevoegen
            </button>
        </div>

        @if($customer->notes->isEmpty())
            <p class="text-sm text-gray-400">Nog geen notities.</p>
        @else
            <div class="space-y-3">
                @foreach($customer->notes as $note)
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-3">
                    <div class="flex items-start justify-between">
                        <p class="text-sm text-gray-800 whitespace-pre-line">{{ $note->body }}</p>
                        @if($note->user_id === auth()->id() || auth()->user()->isAdmin())
                        <button wire:click="deleteNote({{ $note->id }})"
                            class="ml-3 text-gray-300 hover:text-red-400 text-xs shrink-0">
                            ✕
                        </button>
                        @endif
                    </div>
                    <div class="text-xs text-gray-400 mt-2">
                        {{ $note->user?->name ?? 'Onbekend' }}
                        &nbsp;·&nbsp;
                        {{ $note->created_at->format('d-m-Y H:i') }}
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>

</div>
