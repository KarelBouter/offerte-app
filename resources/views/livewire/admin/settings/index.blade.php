<div x-data="{ tab: 'algemeen' }">
    <x-breadcrumb :items="[['label' => 'Beheer', 'route' => 'beheer.dashboard'], ['label' => 'Instellingen']]"/>

    {{-- Tabs --}}
    <div class="mb-6 flex gap-1 border-b border-gray-200">
        <button x-on:click="tab = 'algemeen'"
                x-bind:class="tab === 'algemeen' ? 'border-b-2 border-blue-600 text-blue-700 font-semibold' : 'text-gray-500 hover:text-gray-700'"
                class="px-4 pb-3 text-sm transition-colors">
            Algemeen
        </button>
        <button x-on:click="tab = 'taken'"
                x-bind:class="tab === 'taken' ? 'border-b-2 border-blue-600 text-blue-700 font-semibold' : 'text-gray-500 hover:text-gray-700'"
                class="px-4 pb-3 text-sm transition-colors">
            Automatische taken
        </button>
        <button x-on:click="tab = 'pdf'"
                x-bind:class="tab === 'pdf' ? 'border-b-2 border-blue-600 text-blue-700 font-semibold' : 'text-gray-500 hover:text-gray-700'"
                class="px-4 pb-3 text-sm transition-colors">
            PDF opmaak &amp; teksten
        </button>
    </div>

    {{-- Tab 1: Algemeen --}}
    <div x-show="tab === 'algemeen'" class="max-w-2xl space-y-5">

        @if(session('success'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)"
             x-show="show" x-transition
             class="flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 text-sm px-4 py-3 rounded-lg">
            <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            {{ session('success') }}
        </div>
        @endif

        {{-- Bedrijfsgegevens --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-5">Bedrijfsinformatie</h2>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Bedrijfsnaam <span class="text-red-500">*</span></label>
                    <input wire:model="company_name" type="text"
                           class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"/>
                    @error('company_name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Vestigingsadres <span class="text-red-500">*</span></label>
                    <input wire:model="company_address" type="text"
                           class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"/>
                    @error('company_address') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">KvK-nummer <span class="text-red-500">*</span></label>
                        <input wire:model="company_kvk" type="text"
                               class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"/>
                        @error('company_kvk') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Naam vertegenwoordiger <span class="text-red-500">*</span></label>
                        <input wire:model="company_representative" type="text"
                               class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
                               placeholder="Naam — Functie"/>
                        @error('company_representative') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">E-mailadres bedrijf</label>
                        <input wire:model="company_email" type="email"
                               class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"/>
                        @error('company_email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Telefoonnummer bedrijf</label>
                        <input wire:model="company_phone" type="tel"
                               class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"/>
                    </div>
                </div>
            </div>
        </div>

        {{-- Offerte-instellingen --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-5">Offerte-instellingen</h2>

            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Geldigheidsduur <span class="text-red-500">*</span></label>
                        <div class="flex items-center gap-2">
                            <input wire:model="quote_validity_days" type="number" min="1"
                                   class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"/>
                            <span class="text-sm text-gray-500 whitespace-nowrap">dagen</span>
                        </div>
                        @error('quote_validity_days') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">BTW-percentage <span class="text-red-500">*</span></label>
                        <div class="flex items-center gap-2">
                            <input wire:model="vat_percentage" type="number" min="0" max="100" step="0.1"
                                   class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"/>
                            <span class="text-sm text-gray-500">%</span>
                        </div>
                        @error('vat_percentage') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Standaard notitie onderaan offerte
                        <span class="text-gray-400 font-normal text-xs">(optioneel, verschijnt in PDF)</span>
                    </label>
                    <textarea wire:model="default_quote_note" rows="3"
                              class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
                              placeholder="Bijv. 'Neem bij vragen contact op via info@proudinnovations.nl'"></textarea>
                    @error('default_quote_note') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- Logo --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-5">Bedrijfslogo</h2>

            @if($currentLogoPath)
            <div class="mb-4 p-4 bg-gray-50 rounded-lg border border-gray-200 inline-block">
                <img src="{{ Storage::url($currentLogoPath) }}" alt="Huidig logo" class="h-16 object-contain"/>
                <p class="text-xs text-gray-400 mt-2">Huidig logo</p>
            </div>
            @endif

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    {{ $currentLogoPath ? 'Nieuw logo uploaden' : 'Logo uploaden' }}
                    <span class="text-gray-400 font-normal text-xs">(JPG of PNG, max. 2 MB)</span>
                </label>
                <input wire:model="logo" type="file" accept="image/jpeg,image/png"
                       class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"/>
                @error('logo') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror

                @if($logo)
                <div class="mt-3">
                    <img src="{{ $logo->temporaryUrl() }}" alt="Voorbeeld" class="h-16 object-contain rounded"/>
                    <p class="text-xs text-gray-400 mt-1">Voorbeeld nieuw logo</p>
                </div>
                @endif
            </div>
        </div>

        {{-- Handtekening Proud Innovations --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-3">Handtekening (namens Proud Innovations)</h2>
            <p class="text-xs text-gray-500 mb-4">
                Deze handtekening wordt op de PDF geplaatst nadat u een ondertekende offerte
                definitief bevestigt. Upload een PNG of JPG met transparante of witte achtergrond.
            </p>

            @if($currentSignaturePath)
            <div class="mb-4 p-4 bg-gray-50 rounded-lg border border-gray-200 inline-block">
                <img src="{{ Storage::url($currentSignaturePath) }}" alt="Huidige handtekening" class="h-16 object-contain"/>
                <p class="text-xs text-gray-400 mt-2">Huidige handtekening</p>
            </div>
            @endif

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    {{ $currentSignaturePath ? 'Nieuwe handtekening uploaden' : 'Handtekening uploaden' }}
                    <span class="text-gray-400 font-normal text-xs">(JPG of PNG, max. 2 MB)</span>
                </label>
                <input wire:model="company_signature" type="file" accept="image/jpeg,image/png"
                       class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"/>
                @error('company_signature') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror

                @if($company_signature)
                <div class="mt-3">
                    <img src="{{ $company_signature->temporaryUrl() }}" alt="Voorbeeld" class="h-16 object-contain rounded"/>
                    <p class="text-xs text-gray-400 mt-1">Voorbeeld nieuwe handtekening</p>
                </div>
                @endif
            </div>
        </div>

        <div class="flex justify-end">
            <button wire:click="save"
                    wire:loading.attr="disabled"
                    class="px-5 py-2.5 rounded-lg text-sm font-medium text-white shadow-sm"
                    style="background-color: #1B3A6B;">
                <span wire:loading.remove>Opslaan</span>
                <span wire:loading>Opslaan…</span>
            </button>
        </div>
    </div>

    {{-- Tab 2: Automatische taken --}}
    <div x-show="tab === 'taken'" class="max-w-4xl">
        <livewire:admin.settings.auto-task-templates />
    </div>

    {{-- Tab 3: PDF opmaak & teksten --}}
    <div x-show="tab === 'pdf'" class="max-w-3xl">
        <livewire:admin.settings.pdf-settings />
    </div>
</div>
