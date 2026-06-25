<x-mail::message>
Beste {{ $quote->customer->contact_name }},

Proud Innovations B.V. heeft een offerte voor u opgesteld.

U kunt de offerte bekijken en ondertekenen via de onderstaande link.
Deze link is geldig tot **{{ $quote->sign_token_expires_at?->format('d-m-Y') }}**.

<x-mail::button :url="$signUrl">
Offerte bekijken en ondertekenen
</x-mail::button>

Heeft u vragen? Neem contact op via info@proudinnovations.nl

Met vriendelijke groet,<br>
Proud Innovations B.V.
</x-mail::message>
