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
        <button x-on:click="tab = 'email'"
                x-bind:class="tab === 'email' ? 'border-b-2 border-blue-600 text-blue-700 font-semibold' : 'text-gray-500 hover:text-gray-700'"
                class="px-4 pb-3 text-sm transition-colors">
            E-mail
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

        {{-- Handtekening & betaalafspraken --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-5">Handtekening &amp; betaalafspraken</h2>

            <div class="space-y-5">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Akkoord bij offerte</label>
                    <div class="space-y-2">
                        <label class="flex items-start gap-3 cursor-pointer">
                            <input type="radio" wire:model="require_signature" value="1"
                                   class="mt-0.5 h-4 w-4 text-blue-600 border-gray-300">
                            <span class="text-sm text-gray-700">
                                <span class="font-medium">Handtekening vereist</span><br>
                                <span class="text-gray-500 text-xs">Klant tekent digitaal in het ondertekeningsvak. De handtekening wordt opgeslagen en in de PDF opgenomen.</span>
                            </span>
                        </label>
                        <label class="flex items-start gap-3 cursor-pointer">
                            <input type="radio" wire:model="require_signature" value="0"
                                   class="mt-0.5 h-4 w-4 text-blue-600 border-gray-300">
                            <span class="text-sm text-gray-700">
                                <span class="font-medium">Akkoord via checkbox volstaat</span><br>
                                <span class="text-gray-500 text-xs">Klant vinkt alleen de akkoord-checkbox aan. Geen tekencanvas. Geschikt voor eenvoudigere overeenkomsten.</span>
                            </span>
                        </label>
                    </div>
                </div>

                <hr class="border-gray-100">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Betaalafspraken eenmalige kosten</label>
                    <div class="space-y-2">
                        <label class="flex items-start gap-3 cursor-pointer">
                            <input type="radio" wire:model="payment_onetime_mode" value="100_vooraf"
                                   class="mt-0.5 h-4 w-4 text-blue-600 border-gray-300">
                            <span class="text-sm text-gray-700">
                                <span class="font-medium">100% bij akkoord / start project</span><br>
                                <span class="text-gray-500 text-xs">Volledige betaling van hardware, installatie en add-ons vóór aanvang werkzaamheden.</span>
                            </span>
                        </label>
                        <label class="flex items-start gap-3 cursor-pointer">
                            <input type="radio" wire:model="payment_onetime_mode" value="50_50"
                                   class="mt-0.5 h-4 w-4 text-blue-600 border-gray-300">
                            <span class="text-sm text-gray-700">
                                <span class="font-medium">50% bij akkoord &mdash; 50% bij oplevering</span><br>
                                <span class="text-gray-500 text-xs">De helft van de eenmalige kosten wordt gefactureerd bij ondertekening/akkoord, de andere helft op de dag van oplevering.</span>
                            </span>
                        </label>
                    </div>
                </div>

                <hr class="border-gray-100">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Servicecontract</label>
                    <div class="space-y-3">
                        <div class="flex items-center gap-3">
                            <input type="checkbox" wire:model="payment_service_yearly_advance" id="service_yearly_advance"
                                   class="h-4 w-4 rounded border-gray-300 text-blue-600">
                            <label for="service_yearly_advance" class="text-sm text-gray-700">
                                Jaarlijks vooraf betaald
                                <span class="text-gray-400 font-normal text-xs">(servicecontract wordt per jaar vooruitbetaald)</span>
                            </label>
                        </div>
                        <div class="flex items-center gap-2">
                            <label class="text-sm text-gray-700 whitespace-nowrap">Betalingstermijn servicecontract:</label>
                            <input wire:model="payment_service_days" type="number" min="1" max="90"
                                   class="w-20 rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"/>
                            <span class="text-sm text-gray-500">dagen</span>
                        </div>
                        @error('payment_service_days') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

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

    {{-- Tab 4: E-mailinstellingen --}}
    <div x-show="tab === 'email'" class="max-w-2xl space-y-5">

        {{-- SMTP-verbinding --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-5">SMTP-verbinding</h2>

            <div class="space-y-4">
                <div class="grid grid-cols-3 gap-4">
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">SMTP-host <span class="text-red-500">*</span></label>
                        <input wire:model="mail_host" type="text" placeholder="smtp.example.com"
                               class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"/>
                        @error('mail_host') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Poort <span class="text-red-500">*</span></label>
                        <input wire:model="mail_port" type="number" min="1" max="65535"
                               class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"/>
                        @error('mail_port') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Encryptie</label>
                    <select wire:model="mail_encryption"
                            class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="tls">TLS (aanbevolen, poort 587)</option>
                        <option value="ssl">SSL (poort 465)</option>
                        <option value="">Geen</option>
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Gebruikersnaam</label>
                        <input wire:model="mail_username" type="text" autocomplete="off"
                               class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"/>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Wachtwoord</label>
                        <input wire:model="mail_password" type="password" autocomplete="new-password"
                               placeholder="Laat leeg om huidig wachtwoord te behouden"
                               class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"/>
                        <p class="mt-1 text-xs text-gray-400">Leeglaten = huidig wachtwoord blijft bewaard.</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Afzender --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-5">Afzender</h2>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Afzendernaam <span class="text-red-500">*</span></label>
                    <input wire:model="mail_from_name" type="text" placeholder="Proud Innovations B.V."
                           class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"/>
                    @error('mail_from_name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Afzenderadres <span class="text-red-500">*</span></label>
                    <input wire:model="mail_from_address" type="email" placeholder="noreply@proudinnovations.nl"
                           class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"/>
                    @error('mail_from_address') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- Onderwerpregels --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-5">Onderwerpregels</h2>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Onderwerp offerte-mail <span class="text-red-500">*</span>
                        <span class="text-gray-400 font-normal text-xs ml-1">gebruik <code class="bg-gray-100 px-1 rounded">{quote_number}</code> voor het offertenummer</span>
                    </label>
                    <input wire:model="mail_subject_quote" type="text"
                           class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"/>
                    @error('mail_subject_quote') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Onderwerp welkomstmail <span class="text-red-500">*</span></label>
                    <input wire:model="mail_subject_welcome" type="text"
                           class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"/>
                    @error('mail_subject_welcome') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- Testmail --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-2">Verbinding testen</h2>
            <p class="text-xs text-gray-500 mb-4">
                Sla eerst de instellingen op en klik dan op "Testmail versturen". De testmail wordt verstuurd naar het ingestelde afzenderadres.
            </p>

            @if($mailTestResult)
                <div class="mb-4 px-4 py-3 rounded-lg text-sm border
                    {{ $mailTestSuccess
                        ? 'bg-green-50 border-green-200 text-green-800'
                        : 'bg-red-50 border-red-200 text-red-700' }}">
                    {{ $mailTestResult }}
                </div>
            @endif

            <button wire:click="sendTestMail"
                    wire:loading.attr="disabled"
                    class="px-4 py-2 rounded-lg text-sm font-medium text-white shadow-sm bg-gray-600 hover:bg-gray-700 transition-colors">
                <span wire:loading.remove wire:target="sendTestMail">Testmail versturen</span>
                <span wire:loading wire:target="sendTestMail">Versturen…</span>
            </button>
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
</div>
