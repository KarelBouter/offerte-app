<div>
    <x-breadcrumb :items="[['label' => 'Offertes', 'route' => 'verkoper.offertes.index'], ['label' => $existingQuoteId ? 'Offerte bewerken' : 'Nieuwe offerte']]"/>

    {{-- ═══════════════════════════════════════════════════════════════════
         WIZARD STEPS INDICATOR
    ═══════════════════════════════════════════════════════════════════════ --}}
    <div class="flex items-center gap-0 mb-8">
        @foreach([1 => 'Klantgegevens', 2 => 'Configuratie', 3 => 'Review'] as $n => $label)
            @php $done = $step > $n; $active = $step === $n; @endphp
            <div class="flex items-center {{ $n < 3 ? 'flex-1' : '' }}">
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold border-2
                        {{ $done  ? 'bg-green-500 border-green-500 text-white' : '' }}
                        {{ $active ? 'border-[#1B3A6B] text-[#1B3A6B]' : '' }}
                        {{ (!$done && !$active) ? 'border-gray-300 text-gray-400' : '' }}">
                        @if($done) ✓ @else {{ $n }} @endif
                    </div>
                    <span class="text-sm font-medium
                        {{ $active ? 'text-[#1B3A6B]' : ($done ? 'text-green-600' : 'text-gray-400') }}">
                        {{ $label }}
                    </span>
                </div>
                @if($n < 3)
                    <div class="flex-1 mx-3 h-px {{ $done ? 'bg-green-400' : 'bg-gray-200' }}"></div>
                @endif
            </div>
        @endforeach
    </div>

    {{-- ═══════════════════════════════════════════════════════════════════
         STAP 1: KLANTGEGEVENS
    ═══════════════════════════════════════════════════════════════════════ --}}
    @if($step === 1)
    <div class="max-w-2xl">

        {{-- Customer search --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-5" x-data="{ open: false }">
            <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-4">Bestaande klant zoeken</h3>

            @if($existingCustomerId)
                <div class="flex items-center justify-between bg-blue-50 border border-blue-200 rounded-lg px-4 py-3">
                    <div>
                        <p class="text-sm font-medium text-blue-800">{{ $companyName }}</p>
                        <p class="text-xs text-blue-600">KvK: {{ $kvkNumber }} · {{ $contactName }}</p>
                    </div>
                    <button wire:click="clearCustomer" class="text-xs text-blue-600 hover:text-blue-800 underline">
                        Andere klant
                    </button>
                </div>
            @else
                <div class="relative">
                    <input wire:model.live.debounce.300ms="customerSearch"
                           type="text"
                           placeholder="Zoek op bedrijfsnaam of KvK-nummer…"
                           class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"/>

                    @if(count($customerSuggestions) > 0)
                        <div class="absolute z-20 w-full bg-white border border-gray-200 rounded-lg shadow-lg mt-1 max-h-64 overflow-y-auto">
                            @foreach($customerSuggestions as $c)
                                <button wire:click="selectCustomer({{ $c['id'] }})"
                                        class="w-full text-left px-4 py-3 hover:bg-gray-50 border-b border-gray-100 last:border-0">
                                    <p class="text-sm font-medium text-gray-800">{{ $c['company_name'] }}</p>
                                    <p class="text-xs text-gray-500">KvK: {{ $c['kvk_number'] }} · {{ $c['contact_name'] }}</p>
                                </button>
                            @endforeach
                        </div>
                    @endif
                </div>
                <p class="text-xs text-gray-400 mt-2">Vul een nieuwe klant in als je geen bestaande klant kiest.</p>
            @endif
        </div>

        {{-- Customer form --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-4">Klantgegevens</h3>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Bedrijfsnaam <span class="text-red-500">*</span></label>
                    <input wire:model="companyName" type="text"
                           @if($existingCustomerId) readonly class="w-full rounded-lg border-gray-200 bg-gray-50 text-sm"
                           @else class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
                           @endif/>
                    @error('companyName') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Vestigingsadres <span class="text-red-500">*</span></label>
                    <input wire:model="address" type="text"
                           @if($existingCustomerId) readonly class="w-full rounded-lg border-gray-200 bg-gray-50 text-sm"
                           @else class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
                           @endif/>
                    @error('address') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">KvK-nummer <span class="text-red-500">*</span></label>
                    <input wire:model="kvkNumber" type="text"
                           @if($existingCustomerId) readonly class="w-full rounded-lg border-gray-200 bg-gray-50 text-sm"
                           @else class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
                           @endif/>
                    @error('kvkNumber') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Naam contactpersoon <span class="text-red-500">*</span></label>
                    <input wire:model="contactName" type="text"
                           @if($existingCustomerId) readonly class="w-full rounded-lg border-gray-200 bg-gray-50 text-sm"
                           @else class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
                           @endif/>
                    @error('contactName') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">E-mailadres <span class="text-red-500">*</span></label>
                    <input wire:model="contactEmail" type="email"
                           @if($existingCustomerId) readonly class="w-full rounded-lg border-gray-200 bg-gray-50 text-sm"
                           @else class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
                           @endif/>
                    @error('contactEmail') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Telefoonnummer</label>
                    <input wire:model="contactPhone" type="tel"
                           @if($existingCustomerId) readonly class="w-full rounded-lg border-gray-200 bg-gray-50 text-sm"
                           @else class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
                           @endif/>
                </div>

                <div class="sm:col-span-2">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input wire:model.live="differentInstallAddress" type="checkbox"
                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500"/>
                        <span class="text-sm font-medium text-gray-700">Installatieadres wijkt af van klantadres</span>
                    </label>
                </div>

                @if($differentInstallAddress)
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Installatieadres <span class="text-red-500">*</span></label>
                    <input wire:model="installationAddress" type="text"
                           class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
                           placeholder="Straat, huisnummer, postcode, plaats"/>
                    @error('installationAddress') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                @endif
            </div>
        </div>

        <div class="flex justify-end mt-5">
            <button wire:click="nextStep"
                    class="px-6 py-2.5 rounded-lg text-sm font-medium text-white shadow-sm"
                    style="background-color: #1B3A6B;">
                Volgende stap →
            </button>
        </div>
    </div>
    @endif

    {{-- ═══════════════════════════════════════════════════════════════════
         STAP 2: CONFIGURATOR
    ═══════════════════════════════════════════════════════════════════════ --}}
    @if($step === 2)
    <div class="flex gap-6 items-start">

        {{-- LEFT: Product configurator --}}
        <div class="flex-1 space-y-5 min-w-0">

            {{-- Exclude warnings --}}
            @foreach($excludeMessages as $msg)
                <div class="flex items-start gap-3 bg-amber-50 border border-amber-200 rounded-lg px-4 py-3 text-sm text-amber-800">
                    <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                    <span>{{ $msg }}</span>
                </div>
            @endforeach

            {{-- ── HARDWARE ──────────────────────────────────────────── --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-5 py-3 border-b border-gray-100 bg-gray-50">
                    <h3 class="font-semibold text-gray-700 text-sm">Hardware — Basisoptie</h3>
                    <p class="text-xs text-gray-400 mt-0.5">Kies één configuratie</p>
                </div>
                @error('hwChoice') <p class="px-5 pt-3 text-xs text-red-600">{{ $message }}</p> @enderror
                <div class="p-4 grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @foreach($productsByCategory['Hardware'] ?? [] as $p)
                        @if(str_starts_with($p->name, 'Optie'))
                            <label class="relative flex flex-col gap-1 rounded-xl border-2 p-4 cursor-pointer transition-all
                                {{ $hwChoice == $p->id ? 'border-[#1B3A6B] bg-blue-50' : 'border-gray-200 hover:border-gray-300' }}">
                                <input wire:model.live="hwChoice" type="radio" value="{{ $p->id }}"
                                       class="absolute top-3 right-3 text-blue-600"/>
                                <span class="font-medium text-sm text-gray-800">{{ $p->name }}</span>
                                <span class="text-xs text-gray-500 leading-relaxed">{{ Str::limit($p->description, 80) }}</span>
                                <span class="mt-1 text-sm font-semibold {{ $p->is_price_on_quote ? 'text-amber-600' : 'text-gray-700' }}">
                                    {{ $p->is_price_on_quote ? 'Op offerte' : '€ '.number_format($p->unit_price, 2, ',', '.') }}
                                </span>
                            </label>
                        @endif
                    @endforeach
                </div>

                {{-- UPS as separate checkbox --}}
                @php $ups = ($productsByCategory['Hardware'] ?? collect())->firstWhere('name', 'UPS') @endphp
                @if($ups)
                <div class="px-4 pb-4">
                    <label class="flex items-center gap-3 rounded-xl border border-gray-200 p-4 cursor-pointer hover:bg-gray-50 transition-colors">
                        <input wire:model.live="qtyInputs.{{ $ups->id }}"
                               type="checkbox" value="1"
                               class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"/>
                        <div class="flex-1">
                            <span class="text-sm font-medium text-gray-800">{{ $ups->name }}</span>
                            <span class="text-xs text-gray-400 ml-2">{{ $ups->description }}</span>
                        </div>
                        <span class="text-sm font-semibold text-gray-700">
                            € {{ number_format($ups->unit_price, 2, ',', '.') }}
                        </span>
                    </label>
                </div>
                @endif
            </div>

            {{-- ── NETWERK ───────────────────────────────────────────── --}}
            @if(($productsByCategory['Netwerk'] ?? collect())->isNotEmpty())
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-5 py-3 border-b border-gray-100 bg-gray-50">
                    <h3 class="font-semibold text-gray-700 text-sm">Netwerk</h3>
                </div>
                <div class="p-4 space-y-3">
                    @foreach($productsByCategory['Netwerk'] as $p)
                        @if(!in_array($p->name, $autoOnlyNames))
                        <div class="flex items-center gap-4 rounded-xl border border-gray-200 p-4">
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-800">{{ $p->name }}</p>
                                <p class="text-xs text-gray-400 mt-0.5">{{ Str::limit($p->description, 80) }}</p>
                            </div>
                            <span class="text-sm text-amber-600 font-medium">
                                {{ $p->is_price_on_quote ? 'Op offerte' : '€ '.number_format($p->unit_price, 2, ',', '.') }}
                            </span>
                            <div class="flex items-center gap-2">
                                <label class="text-xs text-gray-500">Aantal</label>
                                <input wire:model.live="qtyInputs.{{ $p->id }}" type="number" min="0" value="0"
                                       class="w-16 rounded-lg border-gray-300 text-sm shadow-sm text-center focus:ring-blue-500"/>
                            </div>
                        </div>
                        @endif
                    @endforeach
                </div>
            </div>
            @endif

            {{-- ── BEVEILIGING ───────────────────────────────────────── --}}
            @php
                $cameras = ($productsByCategory['Beveiliging'] ?? collect())
                    ->filter(fn($p) => !in_array($p->name, $autoOnlyNames));
            @endphp
            @if($cameras->isNotEmpty())
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-5 py-3 border-b border-gray-100 bg-gray-50">
                    <h3 class="font-semibold text-gray-700 text-sm">Beveiliging</h3>
                    <p class="text-xs text-gray-400 mt-0.5">NVR wordt automatisch toegevoegd</p>
                </div>
                <div class="p-4 space-y-3">
                    @foreach($cameras as $p)
                    <div class="flex items-center gap-4 rounded-xl border border-gray-200 p-4">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-800">{{ $p->name }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">{{ Str::limit($p->description, 80) }}</p>
                        </div>
                        <span class="text-sm text-amber-600 font-medium">Op offerte</span>
                        <div class="flex items-center gap-2">
                            <label class="text-xs text-gray-500">Aantal</label>
                            <input wire:model.live="qtyInputs.{{ $p->id }}" type="number" min="0" value="0"
                                   class="w-16 rounded-lg border-gray-300 text-sm shadow-sm text-center focus:ring-blue-500"/>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- ── INSTALLATIE ───────────────────────────────────────── --}}
            @php
                $installProducts = ($productsByCategory['Installatie'] ?? collect())
                    ->filter(fn($p) => !in_array($p->name, $autoOnlyNames));
            @endphp
            @if($installProducts->isNotEmpty())
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-5 py-3 border-b border-gray-100 bg-gray-50">
                    <h3 class="font-semibold text-gray-700 text-sm">Installatie — Extra</h3>
                    <p class="text-xs text-gray-400 mt-0.5">Basisinstallatie wordt automatisch toegevoegd</p>
                </div>
                <div class="p-4 space-y-3">
                    @foreach($installProducts as $p)
                    <div class="flex items-center gap-4 rounded-xl border border-gray-200 p-4">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-800">{{ $p->name }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">{{ Str::limit($p->description, 80) }}</p>
                        </div>
                        <span class="text-sm font-semibold {{ $p->is_price_on_quote ? 'text-amber-600' : 'text-gray-700' }}">
                            {{ $p->is_price_on_quote ? 'Op offerte' : '€ '.number_format($p->unit_price, 2, ',', '.') }}
                        </span>
                        <div class="flex items-center gap-2">
                            <label class="text-xs text-gray-500">Aantal</label>
                            <input wire:model.live="qtyInputs.{{ $p->id }}" type="number" min="0" value="0"
                                   class="w-16 rounded-lg border-gray-300 text-sm shadow-sm text-center focus:ring-blue-500"/>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- ── SERVICE ───────────────────────────────────────────── --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-5 py-3 border-b border-gray-100 bg-gray-50">
                    <h3 class="font-semibold text-gray-700 text-sm">Servicecontract</h3>
                    <p class="text-xs text-gray-400 mt-0.5">Kies één servicecontract (verplicht)</p>
                </div>
                @error('svcChoice') <p class="px-5 pt-3 text-xs text-red-600">{{ $message }}</p> @enderror
                <div class="p-4 grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @foreach($productsByCategory['Service'] ?? [] as $p)
                        <label class="relative flex flex-col gap-1 rounded-xl border-2 p-4 cursor-pointer transition-all
                            {{ $svcChoice == $p->id ? 'border-[#1B3A6B] bg-blue-50' : 'border-gray-200 hover:border-gray-300' }}">
                            <input wire:model.live="svcChoice" type="radio" value="{{ $p->id }}"
                                   class="absolute top-3 right-3 text-blue-600"/>
                            <span class="font-medium text-sm text-gray-800">{{ $p->name }}</span>
                            <span class="text-xs text-gray-500 leading-relaxed">{{ Str::limit($p->description, 80) }}</span>
                            <span class="mt-1 text-sm font-semibold text-gray-700">
                                € {{ number_format($p->unit_price, 2, ',', '.') }} / {{ $p->unit }}
                            </span>
                        </label>
                    @endforeach
                </div>
            </div>

            {{-- ── AUTO-ADDED ITEMS ──────────────────────────────────── --}}
            @if(count($autoItems) > 0)
            <div class="bg-blue-50 rounded-xl border border-blue-200 overflow-hidden">
                <div class="px-5 py-3 border-b border-blue-200">
                    <h3 class="font-semibold text-blue-800 text-sm">Automatisch toegevoegd</h3>
                </div>
                <div class="p-4 space-y-2">
                    @foreach($autoItems as $productId => $item)
                        @php $product = $productsByCategory->flatten()->firstWhere('id', (int)$productId) @endphp
                        @if($product)
                        <div class="flex items-center gap-4 bg-white rounded-lg border border-blue-100 px-4 py-3">
                            <div class="flex-1">
                                <div class="flex items-center gap-2">
                                    <span class="text-sm font-medium text-gray-800">{{ $product->name }}</span>
                                    @if($item['is_recommended'])
                                        <span class="text-xs bg-amber-100 text-amber-700 px-1.5 py-0.5 rounded font-medium">Aanbevolen</span>
                                    @else
                                        <span class="text-xs bg-blue-100 text-blue-700 px-1.5 py-0.5 rounded font-medium">Automatisch</span>
                                    @endif
                                </div>
                                @if($item['auto_added_reason'])
                                    <p class="text-xs text-gray-400 mt-0.5">{{ $item['auto_added_reason'] }}</p>
                                @endif
                            </div>
                            <span class="text-xs text-gray-500 font-medium">× {{ $item['quantity'] }}</span>
                            @if($item['is_recommended'])
                                <button wire:click="declineRecommended('{{ $productId }}')"
                                        class="text-xs text-red-500 hover:text-red-700 font-medium transition-colors">
                                    Niet opnemen
                                </button>
                            @endif
                        </div>
                        @endif
                    @endforeach

                    @foreach($declinedRecommended as $productId => $v)
                        @php $product = $productsByCategory->flatten()->firstWhere('id', (int)$productId) @endphp
                        @if($product)
                        <div class="flex items-center gap-4 bg-gray-50 rounded-lg border border-gray-200 px-4 py-3 opacity-60">
                            <div class="flex-1">
                                <span class="text-sm text-gray-500 line-through">{{ $product->name }}</span>
                                <span class="text-xs text-gray-400 ml-2">Niet opgenomen</span>
                            </div>
                            <button wire:click="acceptRecommended('{{ $productId }}')"
                                    class="text-xs text-blue-600 hover:text-blue-800 font-medium">
                                Toch opnemen
                            </button>
                        </div>
                        @endif
                    @endforeach
                </div>
            </div>
            @endif

            {{-- ── NOTITIES ──────────────────────────────────────────── --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Interne notities
                    <span class="text-gray-400 font-normal">(niet zichtbaar in PDF)</span>
                </label>
                <textarea wire:model="notes" rows="3"
                          class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
                          placeholder="Notities voor intern gebruik…"></textarea>
            </div>

            <div class="flex items-center justify-between pt-2">
                <button wire:click="prevStep"
                        class="px-5 py-2.5 rounded-lg text-sm font-medium text-gray-600 bg-white border border-gray-300 hover:bg-gray-50">
                    ← Vorige stap
                </button>
                <button wire:click="nextStep"
                        class="px-6 py-2.5 rounded-lg text-sm font-medium text-white shadow-sm"
                        style="background-color: #1B3A6B;">
                    Volgende stap →
                </button>
            </div>
        </div>

        {{-- RIGHT: Prijs sidebar --}}
        <div class="w-72 flex-shrink-0 sticky top-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-4 py-3 border-b border-gray-100" style="background-color: #1B3A6B;">
                    <h3 class="text-sm font-semibold text-white">Prijsoverzicht</h3>
                    <p class="text-xs text-blue-200 mt-0.5">Excl. BTW tenzij vermeld</p>
                </div>
                <div class="p-4">
                    @if(count($prices['lineItems']) === 0)
                        <p class="text-xs text-gray-400 text-center py-4">Nog geen producten geselecteerd</p>
                    @else
                        <div class="space-y-2 mb-4">
                            @foreach($prices['lineItems'] as $line)
                                <div class="flex items-start justify-between gap-2 text-xs">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-gray-700 leading-tight">
                                            {{ $line['product']->name }}
                                            @if($line['item']['is_auto_added'] ?? false)
                                                <span class="text-blue-500">*</span>
                                            @endif
                                        </p>
                                        <p class="text-gray-400">× {{ $line['qty'] }}</p>
                                    </div>
                                    @if($line['unit_price'] === null)
                                        <span class="text-amber-600 font-medium whitespace-nowrap">Op offerte *</span>
                                    @else
                                        <span class="text-gray-700 font-medium whitespace-nowrap">
                                            € {{ number_format($line['total'], 2, ',', '.') }}
                                        </span>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        {{-- Eenmalig --}}
                        @if($prices['onetimeExclVat'] > 0)
                        <div class="border-t border-gray-100 pt-3 space-y-1.5">
                            <div class="flex justify-between text-xs text-gray-500">
                                <span>Subtotaal eenmalig</span>
                                <span>€ {{ number_format($prices['onetimeExclVat'], 2, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-xs text-gray-500">
                                <span>BTW 21%</span>
                                <span>€ {{ number_format($prices['onetimeVat'], 2, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-sm font-semibold text-gray-800 border-t border-gray-100 pt-1.5">
                                <span>Totaal eenmalig</span>
                                <span>€ {{ number_format($prices['onetimeInclVat'], 2, ',', '.') }}</span>
                            </div>
                        </div>
                        @endif

                        {{-- Per jaar --}}
                        @if($prices['yearlyExclVat'] > 0)
                        <div class="border-t border-gray-100 mt-3 pt-3 space-y-1.5">
                            <div class="flex justify-between text-xs text-gray-500">
                                <span>Servicecontract / jaar</span>
                                <span>€ {{ number_format($prices['yearlyExclVat'], 2, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-xs text-gray-500">
                                <span>BTW 21%</span>
                                <span>€ {{ number_format($prices['yearlyVat'], 2, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-sm font-semibold text-gray-800 border-t border-gray-100 pt-1.5">
                                <span>Totaal per jaar</span>
                                <span>€ {{ number_format($prices['yearlyInclVat'], 2, ',', '.') }}</span>
                            </div>
                        </div>
                        @endif
                    @endif

                    <p class="text-xs text-gray-400 mt-4 leading-relaxed">
                        * Items gemarkeerd met * worden separaat geoffreerd.
                    </p>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- ═══════════════════════════════════════════════════════════════════
         STAP 3: REVIEW EN OPSLAAN
    ═══════════════════════════════════════════════════════════════════════ --}}
    @if($step === 3)
    <div class="max-w-3xl space-y-5">

        {{-- Quote meta --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Offertenummer</p>
                    <p class="font-mono font-semibold text-gray-800">{{ $previewNumber }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">Definitief nummer wordt aangemaakt bij opslaan</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Geldig tot</p>
                    <input wire:model="validUntil" type="date"
                           class="rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"/>
                </div>
            </div>
        </div>

        {{-- Customer summary --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
            <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-3">Klant</h3>
            <div class="grid grid-cols-2 gap-3 text-sm">
                <div><p class="text-xs text-gray-400">Bedrijfsnaam</p><p class="font-medium text-gray-800">{{ $companyName }}</p></div>
                <div><p class="text-xs text-gray-400">KvK</p><p class="text-gray-700">{{ $kvkNumber }}</p></div>
                <div><p class="text-xs text-gray-400">Adres</p><p class="text-gray-700">{{ $address }}</p></div>
                <div><p class="text-xs text-gray-400">Contactpersoon</p><p class="text-gray-700">{{ $contactName }} · {{ $contactEmail }}</p></div>
                @if($differentInstallAddress)
                <div class="col-span-2"><p class="text-xs text-gray-400">Installatieadres</p><p class="text-gray-700">{{ $installationAddress }}</p></div>
                @endif
            </div>
        </div>

        {{-- Configuration table --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-5 py-3 border-b border-gray-100 bg-gray-50">
                <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Configuratie</h3>
            </div>
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100 text-left">
                        <th class="px-5 py-2.5 text-xs font-semibold text-gray-500">Product</th>
                        <th class="px-5 py-2.5 text-xs font-semibold text-gray-500 text-center">Aantal</th>
                        <th class="px-5 py-2.5 text-xs font-semibold text-gray-500 text-right">Eenheidsprijs</th>
                        <th class="px-5 py-2.5 text-xs font-semibold text-gray-500 text-right">Totaal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($prices['lineItems'] as $line)
                        <tr class="{{ $line['item']['is_auto_added'] ?? false ? 'bg-blue-50/30' : '' }}">
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-2">
                                    <span class="font-medium text-gray-800">{{ $line['product']->name }}</span>
                                    @if($line['item']['is_auto_added'] ?? false)
                                        <span class="text-xs bg-blue-100 text-blue-600 px-1.5 py-0.5 rounded">Auto</span>
                                    @endif
                                </div>
                                @if(!empty($line['item']['auto_added_reason']))
                                    <p class="text-xs text-gray-400 mt-0.5">{{ $line['item']['auto_added_reason'] }}</p>
                                @endif
                            </td>
                            <td class="px-5 py-3 text-center text-gray-600">{{ $line['qty'] }}</td>
                            <td class="px-5 py-3 text-right text-gray-600">
                                {{ $line['unit_price'] !== null ? '€ '.number_format($line['unit_price'], 2, ',', '.') : 'Op offerte' }}
                            </td>
                            <td class="px-5 py-3 text-right font-medium text-gray-800">
                                {{ $line['total'] !== null ? '€ '.number_format($line['total'], 2, ',', '.') : '—' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="border-t-2 border-gray-200">
                    @if($prices['onetimeExclVat'] > 0)
                    <tr class="bg-gray-50">
                        <td colspan="3" class="px-5 py-2.5 text-sm font-semibold text-gray-700">Totaal eenmalig excl. BTW</td>
                        <td class="px-5 py-2.5 text-right text-sm font-bold text-gray-800">€ {{ number_format($prices['onetimeExclVat'], 2, ',', '.') }}</td>
                    </tr>
                    @endif
                    @if($prices['yearlyExclVat'] > 0)
                    <tr class="bg-gray-50">
                        <td colspan="3" class="px-5 py-2.5 text-sm font-semibold text-gray-700">Servicecontract per jaar excl. BTW</td>
                        <td class="px-5 py-2.5 text-right text-sm font-bold text-gray-800">€ {{ number_format($prices['yearlyExclVat'], 2, ',', '.') }}</td>
                    </tr>
                    @endif
                </tfoot>
            </table>
        </div>

        {{-- Notes --}}
        @if($notes)
        <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 text-sm">
            <p class="text-xs font-semibold text-amber-700 uppercase tracking-wide mb-1">Interne notities</p>
            <p class="text-amber-800">{{ $notes }}</p>
        </div>
        @endif

        {{-- Actions --}}
        <div class="flex items-center justify-between pt-2">
            <button wire:click="prevStep"
                    class="px-5 py-2.5 rounded-lg text-sm font-medium text-gray-600 bg-white border border-gray-300 hover:bg-gray-50">
                ← Vorige stap
            </button>
            <div class="flex items-center gap-3">
                <button wire:click="save(false)"
                        wire:loading.attr="disabled"
                        class="px-5 py-2.5 rounded-lg text-sm font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50">
                    <span wire:loading.remove wire:target="save">Opslaan als concept</span>
                    <span wire:loading wire:target="save">Opslaan…</span>
                </button>
                <button wire:click="save(true)"
                        wire:loading.attr="disabled"
                        class="px-6 py-2.5 rounded-lg text-sm font-medium text-white shadow-sm"
                        style="background-color: #1B3A6B;">
                    <span wire:loading.remove wire:target="save">Opslaan en PDF genereren</span>
                    <span wire:loading wire:target="save">Opslaan…</span>
                </button>
            </div>
        </div>
    </div>
    @endif
</div>
