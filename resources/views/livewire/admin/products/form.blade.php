<div>
    <x-breadcrumb :items="[['label' => 'Beheer', 'route' => 'beheer.dashboard'], ['label' => 'Producten', 'route' => 'beheer.producten.index'], ['label' => 'Product']]"/>

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

                {{-- Switch poorten --}}
                <div class="sm:col-span-2">
                    <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3 mt-2">Switch poorten (optioneel — alleen invullen voor switches)</h4>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Totaal aantal poorten</label>
                            <input wire:model.live="switch_ports_total" type="number" min="1"
                                   class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                   placeholder="bijv. 8"/>
                            @error('switch_ports_total') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Waarvan PoE poorten
                                <span class="text-xs text-gray-400 font-normal">— de rest is standaard</span>
                            </label>
                            <input wire:model.live="switch_ports_poe" type="number" min="0"
                                   class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                   placeholder="bijv. 8"/>
                            @error('switch_ports_poe') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    @if($switch_ports_total)
                    <div class="mt-3 bg-blue-50 border border-blue-100 rounded-lg px-4 py-3 text-xs text-blue-700 space-y-1">
                        <p>Totaal poorten: <strong>{{ $switch_ports_total }}</strong></p>
                        <p>Waarvan PoE: <strong>{{ $switch_ports_poe ?? 0 }}</strong> &nbsp;|&nbsp; Standaard: <strong>{{ $switch_ports_total - ($switch_ports_poe ?? 0) }}</strong></p>
                        <p>Gereserveerd voor uplink: <strong>1 standaard poort</strong></p>
                        <p class="border-t border-blue-200 pt-1 mt-1">
                            Beschikbaar voor apparaten:
                            <strong>{{ $switch_ports_poe ?? 0 }} PoE</strong> +
                            <strong>{{ max(0, ($switch_ports_total - ($switch_ports_poe ?? 0)) - 1) }} standaard</strong>
                            = <strong>{{ $switch_ports_total - 1 }} poorten totaal</strong>
                        </p>
                    </div>
                    @endif
                </div>

                {{-- PoE wattage --}}
                <div class="sm:col-span-2">
                    <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3 mt-2">PoE (optioneel)</h4>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                PoE output (W)
                                <span class="text-xs text-gray-400 font-normal">— voor switches: max. te leveren wattage</span>
                            </label>
                            <input wire:model="poe_wattage_output" type="number" min="0"
                                   class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                   placeholder="bijv. 196"/>
                            @error('poe_wattage_output') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                PoE input (W)
                                <span class="text-xs text-gray-400 font-normal">— voor AP's/camera's: verbruik per stuk</span>
                            </label>
                            <input wire:model="poe_wattage_input" type="number" min="0"
                                   class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                   placeholder="bijv. 20"/>
                            @error('poe_wattage_input') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                {{-- Netwerkpoorten benodigd --}}
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Netwerkpoorten benodigd (optioneel)
                        <span class="text-xs text-gray-400 font-normal">— voor servers/nodes: aantal switch-poorten per unit</span>
                    </label>
                    <input wire:model="poorten_benodigd" type="number" min="0"
                           class="w-32 rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
                           placeholder="bijv. 1"/>
                    <p class="text-xs text-gray-400 mt-1">Vul in voor hardware die een eigen poort op de switch gebruikt (server, NUC-node). Telt mee in de automatische switch-selectie. Laat leeg voor producten die geen switchpoort gebruiken (UPS, SSD, switches zelf).</p>
                    @error('poorten_benodigd') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- Werkbon --}}
                <div class="sm:col-span-2">
                    <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3 mt-2">Werkbon</h4>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Installatie-instructie (optioneel)
                                <span class="text-xs text-gray-400 font-normal">— standaard checklist-tekst op de werkbon</span>
                            </label>
                            <textarea wire:model="installatie_instructie" rows="3"
                                      class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                      placeholder="bijv. Verbind netwerkkabel aan poort 1 van de switch. Configureer VLAN 10 via beheerinterface."></textarea>
                            <p class="text-xs text-gray-400 mt-1">Verschijnt onder het product op de werkbon. Op de offerte niet zichtbaar.</p>
                            @error('installatie_instructie') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Zichtbaarheid op werkbon</label>
                            <select wire:model="werkbon_zichtbaarheid"
                                    class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                @foreach(\App\Support\WerkbonAantekeningen::ZICHTBAARHEID as $waarde => $label)
                                    <option value="{{ $waarde }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            <p class="text-xs text-gray-400 mt-1">
                                <strong>Automatisch</strong>: altijd tonen. <strong>Verbergen</strong>: weglaten tenzij er een offertespecifieke notitie of aantekening is.
                            </p>
                            @error('werkbon_zichtbaarheid') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                {{-- Prijs per meter --}}
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Prijs per meter (optioneel)
                        <span class="text-xs text-gray-400 font-normal">— voor kabelproducten: unit_price is dan het starttarief</span>
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 text-sm">€</span>
                        <input wire:model="price_per_meter" type="number" step="0.01" min="0"
                               class="w-full pl-7 rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
                               placeholder="bijv. 1.00"/>
                    </div>
                    <p class="text-xs text-gray-400 mt-1">Als dit is ingevuld, vraagt de offerte bouwer om een lengte in meters. Totaalprijs = starttarief + (meters × prijs per meter).</p>
                    @error('price_per_meter') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
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
