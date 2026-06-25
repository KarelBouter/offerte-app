<div class="max-w-lg space-y-5">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h2 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-5">Persoonlijke gegevens</h2>

        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Naam <span class="text-red-500">*</span></label>
                <input wire:model="name" type="text"
                       class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"/>
                @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">E-mailadres <span class="text-red-500">*</span></label>
                <input wire:model="email" type="email"
                       class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"/>
                @error('email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="border-t border-gray-100 mt-5 pt-5">
            <h3 class="text-sm font-semibold text-gray-700 mb-3">Wachtwoord wijzigen
                <span class="text-gray-400 font-normal text-xs">(optioneel)</span>
            </h3>

            <div class="space-y-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Huidig wachtwoord</label>
                    <input wire:model="currentPassword" type="password"
                           class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"/>
                    @error('currentPassword') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nieuw wachtwoord</label>
                        <input wire:model="newPassword" type="password"
                               class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"/>
                        @error('newPassword') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Bevestigen</label>
                        <input wire:model="newPasswordConfirmation" type="password"
                               class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"/>
                        @error('newPasswordConfirmation') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-end mt-6">
            <button wire:click="save"
                    wire:loading.attr="disabled"
                    class="px-5 py-2.5 rounded-lg text-sm font-medium text-white shadow-sm"
                    style="background-color: #1B3A6B;">
                <span wire:loading.remove>Opslaan</span>
                <span wire:loading>Opslaan…</span>
            </button>
        </div>
    </div>

    <div class="bg-gray-50 rounded-xl border border-gray-200 p-4 text-xs text-gray-500">
        <p>Ingelogd als <strong class="text-gray-700">{{ auth()->user()->email }}</strong></p>
        <p class="mt-1">Rol: <strong class="text-gray-700">{{ auth()->user()->role === 'admin' ? 'Beheerder' : 'Verkoper' }}</strong></p>
    </div>
</div>
