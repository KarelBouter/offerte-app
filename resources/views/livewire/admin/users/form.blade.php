<div>
    <x-breadcrumb :items="[['label' => 'Beheer', 'route' => 'beheer.dashboard'], ['label' => 'Gebruikers', 'route' => 'beheer.gebruikers.index'], ['label' => 'Gebruiker']]"/>

<div class="max-w-lg">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
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

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Rol <span class="text-red-500">*</span></label>
                    <select wire:model="role"
                            class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="verkoper">Verkoper</option>
                        <option value="samensteller">Samensteller</option>
                        <option value="admin">Beheerder</option>
                    </select>
                    @error('role') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-end pb-1">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input wire:model="isActive" type="checkbox"
                               class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"/>
                        <span class="text-sm font-medium text-gray-700">Account actief</span>
                    </label>
                </div>
            </div>

            <div class="border-t border-gray-100 pt-4">
                <p class="text-xs text-gray-500 mb-3">
                    {{ $userId ? 'Laat leeg om het wachtwoord niet te wijzigen.' : 'Verplicht bij aanmaken. Wordt per e-mail verstuurd.' }}
                </p>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Wachtwoord @if(!$userId) <span class="text-red-500">*</span> @endif
                        </label>
                        <input wire:model="password" type="password"
                               class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"/>
                        @error('password') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Bevestigen @if(!$userId) <span class="text-red-500">*</span> @endif
                        </label>
                        <input wire:model="passwordConfirmation" type="password"
                               class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"/>
                        @error('passwordConfirmation') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-between mt-6 pt-5 border-t border-gray-100">
            <a href="{{ route('beheer.gebruikers.index') }}"
               class="text-sm text-gray-500 hover:text-gray-700">← Terug</a>
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
