@props([
    'name',
    'title',
    'message'     => '',
    'cancelLabel' => 'Annuleren',
    'variant'     => 'default', // default | danger
])

<x-modal :name="$name" maxWidth="sm">
    <div class="{{ $variant === 'danger' ? 'border-t-4 border-red-500' : 'border-t-4 border-blue-700' }} rounded-t-lg">
        <div class="p-6">
            <div class="flex items-start gap-3">
                @if($variant === 'danger')
                    <div class="flex-shrink-0 mt-0.5">
                        <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                @else
                    <div class="flex-shrink-0 mt-0.5">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                @endif
                <div class="flex-1 min-w-0">
                    <h3 class="text-base font-semibold text-gray-900">{{ $title }}</h3>
                    @if($message)
                        <p class="mt-1 text-sm text-gray-600">{{ $message }}</p>
                    @endif
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 mt-6">
                <button type="button"
                        x-on:click="$dispatch('close-modal', '{{ $name }}')"
                        class="px-4 py-2 rounded-lg text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 transition-colors">
                    {{ $cancelLabel }}
                </button>
                {{ $slot }}
            </div>
        </div>
    </div>
</x-modal>
