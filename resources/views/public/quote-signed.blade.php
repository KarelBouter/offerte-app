<x-layouts.public title="Offerte ondertekend">
<div class="max-w-lg mx-auto text-center space-y-6 py-16">
    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto">
        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
    </div>
    <h1 class="text-2xl font-bold text-gray-900">Offerte ondertekend</h1>
    <p class="text-gray-600">
        Bedankt, <strong>{{ $quote->customer->contact_name }}</strong>.<br>
        Offerte <strong>{{ $quote->quote_number }}</strong> is succesvol ondertekend op
        {{ $quote->signed_at->format('d-m-Y \o\m H:i') }}.
    </p>
    <p class="text-sm text-gray-400">
        U ontvangt geen automatische bevestigingsmail. Bewaar deze pagina als referentie,
        of neem contact op met Proud Innovations B.V.
    </p>
</div>
</x-layouts.public>
