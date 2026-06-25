<div class="max-w-2xl">
    @if(session('success'))
    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)"
         x-show="show" x-transition
         class="mb-5 flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 text-sm px-4 py-3 rounded-lg">
        <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
        {{ session('success') }}
    </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h2 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-5">Bedrijfsgegevens</h2>

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
                    Vertegenwoordigd door
                    <span class="text-red-500">*</span>
                    <span class="text-gray-400 font-normal text-xs">(naam + functie, bijv. Pascal Versluis — Directeur)</span>
                </label>
                <input wire:model="company_representative" type="text"
                       class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
                       placeholder="Naam — Functie"/>
                @error('company_representative') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="pt-2 border-t border-gray-100">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Standaard geldigheidsduur offerte
                    <span class="text-red-500">*</span>
                </label>
                <div class="flex items-center gap-2">
                    <input wire:model="quote_validity_days" type="number" min="1"
                           class="w-32 rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"/>
                    <span class="text-sm text-gray-500">dagen</span>
                </div>
                @error('quote_validity_days') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="flex justify-end mt-6">
            <button wire:click="save"
                    class="px-5 py-2.5 rounded-lg text-sm font-medium text-white shadow-sm"
                    style="background-color: #1B3A6B;">
                Opslaan
            </button>
        </div>
    </div>
</div>
