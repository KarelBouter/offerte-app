<x-layouts.public title="Offerte verlopen">
<div class="max-w-lg mx-auto text-center py-20">
    <div class="w-14 h-14 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-5">
        <svg class="w-7 h-7 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
        </svg>
    </div>
    <h1 class="text-xl font-bold text-gray-900 mb-2">Deze offerte-link is verlopen</h1>
    <p class="text-sm text-gray-600 mb-6">
        De link voor offerte <strong>{{ $quote->quote_number }}</strong> is niet meer geldig.<br>
        Neem contact op met uw accountmanager voor een nieuwe link.
    </p>
    <a href="mailto:info@proudinnovations.nl?subject=Nieuwe offerte-link aanvragen — {{ $quote->quote_number }}"
       class="inline-flex items-center gap-2 bg-blue-700 hover:bg-blue-800 text-white font-medium text-sm px-5 py-2.5 rounded-lg transition-colors">
        Contact opnemen
    </a>
</div>
</x-layouts.public>
