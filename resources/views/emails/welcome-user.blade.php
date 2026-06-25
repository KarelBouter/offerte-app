<x-mail::message>
Beste {{ $user->name }},

Je account is aangemaakt voor de offerte-applicatie van Proud Innovations B.V.

**Inloggegevens:**

| | |
|---|---|
| **URL** | {{ config('app.url') }} |
| **E-mailadres** | {{ $user->email }} |
| **Tijdelijk wachtwoord** | `{{ $plainPassword }}` |

Wijzig je wachtwoord na de eerste inlog via je profielpagina.

<x-mail::button :url="config('app.url')">
Naar de applicatie
</x-mail::button>

Met vriendelijke groet,<br>
Proud Innovations B.V.
</x-mail::message>
