<div>
    <x-breadcrumb :items="['label' => 'Beheer', 'route' => 'beheer.dashboard'], ['label' => 'Producten', 'route' => 'beheer.producten.index'], ['label' => 'Product']]"/>

    {{-- Back + title --}}
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('beheer.producten.index') }}"
           class="text-gray-400 hover:text-gray-600 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
        </a>
        <h2 class="text-lg font-semibold text-gray-800">
            {{ $this->product?->exists ? 'Product bewerken: ' . $this->product->name : 'Nieuw product aanmaken' }}
        </h2>
    </div>

    <form wire:submit="save" class="space-y-6">

        {{-- Basisgegevens --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-5">Basisgegevens</h3>
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

                {{-- Naam --}}
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Naam <span class="text-red-500">*</span>
                    </label>
                    <input wire:model="name" type="text"
                           class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
                           placeholder="Productnaam"/>
                    @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- SKU --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">SKU</label>
                    <input wire:model="sku" type="text"
                           class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
                           placeholder="Artikelnummer (optioneel)"/>
                    @error('sku') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- Categorie --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Categorie <span class="text-red-500">*</span>
                    </label>
                    <select wire:model="category"
                            class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @foreach(['Hardware', 'Netwerk', 'Beveiliging', 'Installatie', 'Service'] as $cat)
                            <option value="{{ $cat }}">{{ $cat }}</option>
                        @endforeach
                    </select>
                    @error('category') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- Omschrijving --}}
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Omschrijving <span class="text-red-500">*</span>
                    </label>
                    <textarea wire:model="description" rows="3"
                              class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
                              placeholder="Beschrijf het product…"></textarea>
                    @error('description') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- Prijsstelling --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-5">Prijsstelling</h3>
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

                {{-- Prijs op offerte --}}
                <div class="sm:col-span-2">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input wire:model.live="is_price_on_quote" type="checkbox"
                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500"/>
                        <span class="text-sm font-medium text-gray-700">Prijs op offerte</span>
                        <span class="text-xs text-gray-400">(prijs wordt per offerte bepaald)</span>
                    </label>
                </div>

                {{-- Eenheidsprijs --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Eenheidsprijs excl. BTW
                        @unless($is_price_on_quote) <span class="text-red-500">*</span> @endunless
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 text-sm">€</span>
                        <input wire:model="unit_price" type="number" step="0.01" min="0"
                               @if($is_price_on_quote) disabled @endif
                               class="w-full pl-7 rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500 disabled:bg-gray-100 disabled:text-gray-400"/>
                    </div>
                    @error('unit_price') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- Eenheid --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Eenheid <span class="text-red-500">*</span>
                    </label>
                    <select wire:model="unit"
                            class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @foreach(['stuk', 'dag', 'jaar', 'set'] as $u)
                            <option value="{{ $u }}">{{ ucfirst($u) }}</option>
                        @endforeach
                    </select>
                    @error('unit') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- Afname --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-5">Afnamegrenzen</h3>
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Minimale afname</label>
                    <input wire:model="min_quantity" type="number" min="1"
                           class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
                           placeholder="Optioneel"/>
                    @error('min_quantity') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Maximale afname</label>
                    <input wire:model="max_quantity" type="number" min="1"
                           class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
                           placeholder="Optioneel"/>
                    @error('max_quantity') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- Afbeelding --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-5">Afbeelding</h3>

            @if($existingImagePath && ! $image)
                <div class="mb-4">
                    <p class="text-xs text-gray-500 mb-2">Huidige afbeelding:</p>
                    <img src="{{ Storage::url($existingImagePath) }}"
                         alt="Huidige afbeelding"
                         class="h-24 w-24 object-cover rounded-lg border border-gray-200"/>
                </div>
            @endif

            @if($image)
                <div class="mb-4">
                    <p class="text-xs text-gray-500 mb-2">Nieuwe afbeelding (preview):</p>
                    <img src="{{ $image->temporaryUrl() }}"
                         alt="Preview"
                         class="h-24 w-24 object-cover rounded-lg border border-gray-200"/>
                </div>
            @endif

            <label class="block">
                <span class="text-sm font-medium text-gray-700 mb-1 block">
                    {{ $existingImagePath ? 'Afbeelding vervangen' : 'Afbeelding uploaden' }}
                    <span class="text-gray-400 font-normal">(JPG of PNG, max. 2 MB)</span>
                </span>
                <input wire:model="image" type="file" accept="image/jpeg,image/png"
                       class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"/>
            </label>
            <div wire:loading wire:target="image" class="mt-2 text-xs text-blue-600">Bezig met uploaden…</div>
            @error('image') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        {{-- Weergave --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-5">Weergave</h3>
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Volgorde (sort_order)</label>
                    <input wire:model="sort_order" type="number" min="0"
                           class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"/>
                    @error('sort_order') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div class="flex items-end pb-1">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input wire:model="is_active" type="checkbox"
                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500"/>
                        <span class="text-sm font-medium text-gray-700">Product is actief</span>
                    </label>
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex items-center justify-end gap-3 pb-2">
            <a href="{{ route('beheer.producten.index') }}"
               class="px-5 py-2 rounded-lg text-sm font-medium text-gray-600 bg-white border border-gray-300 hover:bg-gray-50 transition-colors">
                Annuleren
            </a>
            <button type="submit"
                    class="px-5 py-2 rounded-lg text-sm font-medium text-white shadow-sm transition-colors"
                    style="background-color: #1B3A6B;"
                    wire:loading.attr="disabled">
                <span wire:loading.remove>Opslaan</span>
                <span wire:loading>Opslaan…</span>
            </button>
        </div>

    </form>
</div>
