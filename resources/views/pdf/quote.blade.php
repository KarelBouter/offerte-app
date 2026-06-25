<!DOCTYPE html>
<html lang="nl">
<head>
<meta charset="utf-8"/>
<style>
@page {
    margin: 20mm 20mm 25mm 20mm;
}
* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}
body {
    font-family: DejaVu Sans, sans-serif;
    font-size: 9.5pt;
    color: #1a1a1a;
    line-height: 1.55;
}

/* ── Fixed header / footer ─────────────────────────────────────────── */
#header {
    position: fixed;
    top: -15mm;
    left: 0; right: 0;
    height: 14mm;
    border-bottom: 1.5pt solid #1B3A6B;
    padding-bottom: 2mm;
}
#header-left {
    float: left;
    font-size: 9pt;
    font-weight: bold;
    color: #1B3A6B;
    margin-top: 4mm;
}
#header-right {
    float: right;
    font-size: 8pt;
    color: #555;
    text-align: right;
    margin-top: 4mm;
}
#footer {
    position: fixed;
    bottom: -20mm;
    left: 0; right: 0;
    height: 10mm;
    border-top: 0.75pt solid #ccc;
    padding-top: 2mm;
    font-size: 7.5pt;
    color: #888;
    text-align: center;
}

/* ── Main content ──────────────────────────────────────────────────── */
.page-break { page-break-before: always; }

h1 {
    font-size: 16pt;
    color: #1B3A6B;
    font-weight: bold;
    margin-bottom: 2mm;
}
h2 {
    font-size: 10.5pt;
    color: #1B3A6B;
    font-weight: bold;
    margin-top: 6mm;
    margin-bottom: 2mm;
    padding-bottom: 1.5mm;
    border-bottom: 0.75pt solid #1B3A6B;
}
h3 {
    font-size: 9.5pt;
    font-weight: bold;
    color: #1B3A6B;
    margin-top: 3mm;
    margin-bottom: 1.5mm;
}
p { margin-bottom: 2mm; }
ul {
    margin: 1.5mm 0 2mm 5mm;
    padding: 0;
}
li { margin-bottom: 0.75mm; list-style-type: disc; }

/* ── Tables ────────────────────────────────────────────────────────── */
table { width: 100%; border-collapse: collapse; margin-bottom: 3mm; }
th {
    background-color: #1B3A6B;
    color: #fff;
    font-size: 8.5pt;
    padding: 2mm 3mm;
    text-align: left;
}
td { padding: 2mm 3mm; font-size: 9pt; border-bottom: 0.5pt solid #e0e0e0; }
tr.total td { font-weight: bold; border-top: 1pt solid #1B3A6B; border-bottom: none; }
tr.sep td { height: 4mm; border: none; }
.text-right { text-align: right; }

/* ── Two-column layout via table ───────────────────────────────────── */
.cols { width: 100%; border-collapse: collapse; margin-bottom: 4mm; }
.cols td { vertical-align: top; padding: 0; border: none; }
.col-left  { width: 48%; padding-right: 4mm; }
.col-right { width: 48%; padding-left: 4mm; border-left: 0.75pt solid #ddd; }

/* ── Party block ───────────────────────────────────────────────────── */
.party-block { font-size: 9pt; line-height: 1.6; }
.party-block .party-title {
    font-size: 9pt;
    font-weight: bold;
    color: #1B3A6B;
    border-bottom: 0.5pt solid #1B3A6B;
    padding-bottom: 1mm;
    margin-bottom: 2mm;
}
.party-block strong { font-weight: bold; }

/* ── Option blocks ──────────────────────────────────────────────────── */
.option-block {
    border: 0.75pt solid #ccc;
    border-radius: 2pt;
    padding: 3mm;
    margin-bottom: 3mm;
}
.option-block.chosen {
    border-color: #1B3A6B;
    background-color: #f0f4fb;
}
.option-title {
    font-weight: bold;
    font-size: 9.5pt;
    color: #1B3A6B;
    margin-bottom: 1.5mm;
}
.checkmark { color: #1B3A6B; font-weight: bold; }
.not-chosen { color: #888; }

/* ── Sign block ────────────────────────────────────────────────────── */
.sign-line { border-bottom: 0.75pt solid #333; height: 8mm; margin-top: 1mm; width: 80%; }
.sign-label { font-size: 8pt; color: #666; margin-top: 1mm; }

/* ── Info box ──────────────────────────────────────────────────────── */
.info-box {
    background-color: #f7f9fc;
    border-left: 3pt solid #1B3A6B;
    padding: 2.5mm 3.5mm;
    margin: 3mm 0;
    font-size: 9pt;
}
</style>
</head>
<body>

{{-- ── Fixed header ──────────────────────────────────────────────────── --}}
<div id="header">
    <div id="header-left">Overeenkomst Kassa Continuïteitsdienst</div>
    <div id="header-right">
        {{ $quote->quote_number }}<br>
        {{ now()->format('d-m-Y') }}
    </div>
    <div style="clear:both;"></div>
</div>

{{-- ── Fixed footer ──────────────────────────────────────────────────── --}}
<div id="footer">
    {{ $settings['company_name'] }} &mdash; pagina <span class="pagenum"></span>
</div>

{{-- ═══════════════════════════════════════════════════════════════════
     TITLE BLOCK
═══════════════════════════════════════════════════════════════════════ --}}
<h1>Overeenkomst<br>Kassa Continuïteitsdienst</h1>
<p style="color:#555; font-size:9pt; margin-bottom:6mm;">
    Offertenummer: <strong>{{ $quote->quote_number }}</strong> &nbsp;|&nbsp;
    Datum: <strong>{{ $quote->created_at->format('d-m-Y') }}</strong> &nbsp;|&nbsp;
    Geldig tot: <strong>{{ $quote->valid_until->format('d-m-Y') }}</strong>
</p>

{{-- ═══════════════════════════════════════════════════════════════════
     ARTIKEL 1 — PARTIJEN
═══════════════════════════════════════════════════════════════════════ --}}
<h2>Artikel 1 &mdash; Partijen</h2>

<table class="cols">
<tr>
    <td class="col-left">
        <div class="party-block">
            <div class="party-title">Opdrachtnemer (leverancier)</div>
            <strong>Naam:</strong> {{ $settings['company_name'] }}<br>
            <strong>Vestigingsadres:</strong> {{ $settings['company_address'] }}<br>
            <strong>KvK-nummer:</strong> {{ $settings['company_kvk'] }}<br>
            <strong>Vertegenwoordigd door:</strong> {{ $settings['company_representative'] }}
        </div>
    </td>
    <td class="col-right">
        <div class="party-block">
            <div class="party-title">Opdrachtgever (klant)</div>
            <strong>Bedrijfsnaam:</strong> {{ $quote->customer->company_name }}<br>
            <strong>Vestigingsadres:</strong> {{ $quote->customer->address }}<br>
            <strong>KvK-nummer:</strong> {{ $quote->customer->kvk_number }}<br>
            <strong>Vertegenwoordigd door:</strong> {{ $quote->customer->contact_name }}<br>
            <strong>Locatie installatie:</strong> {{ $quote->installation_address ?? $quote->customer->address }}
        </div>
    </td>
</tr>
</table>

<p>Partijen komen de hierna genoemde dienstverlening overeen, bestaande uit de levering en installatie van hardware (eenmalig) en een jaarlijks servicecontract (doorlopend), zoals hieronder per onderdeel vastgelegd.</p>

{{-- ═══════════════════════════════════════════════════════════════════
     ARTIKEL 2 — OMSCHRIJVING VAN DE DIENST
═══════════════════════════════════════════════════════════════════════ --}}
<h2>Artikel 2 &mdash; Omschrijving van de dienst</h2>

<p>De Kassa Continuïteitsdienst voorziet in de levering, installatie en het onderhoud van de technische infrastructuur (server-/clusterhardware, netwerkapparatuur en stroomvoorziening) waarop het kassasysteem van Opdrachtgever draait. Doel van de dienst is het waarborgen van een zo hoog mogelijke beschikbaarheid van deze infrastructuur, zodat bedrijfsvoering en omzet van Opdrachtgever zo min mogelijk worden verstoord door technische storingen.</p>

<p><strong>Afbakening:</strong> Proud Innovations B.V. is verantwoordelijk voor de hardware-infrastructuur (server/cluster, netwerk, stroomvoorziening) zoals beschreven in deze overeenkomst. Het kassasysteem zelf (software, licenties, functioneel gebruik) wordt geleverd en ondersteund door Kassacentrum en valt buiten de scope van deze overeenkomst.</p>

{{-- ═══════════════════════════════════════════════════════════════════
     ARTIKEL 3 — TOEPASSELIJKE VOORWAARDEN
═══════════════════════════════════════════════════════════════════════ --}}
<h2>Artikel 3 &mdash; Toepasselijke voorwaarden</h2>

<p>Op deze overeenkomst en op alle door Proud Innovations B.V. te leveren producten en diensten zijn de NLdigital Voorwaarden 2025 van toepassing, gedeponeerd bij de rechtbank Midden-Nederland, locatie Utrecht. Opdrachtgever verklaart een exemplaar van de NLdigital Voorwaarden 2025 te hebben ontvangen en daarvan kennis te hebben genomen.</p>

<p>Voor deze overeenkomst zijn in het bijzonder relevant: hoofdstuk&nbsp;1 (algemene bepalingen, waaronder aansprakelijkheid, overmacht, toepasselijk recht en geschillen), hoofdstuk&nbsp;14 (koop van apparatuur) voor de hardware, en hoofdstuk&nbsp;16 (onderhoud van apparatuur) voor het servicecontract.</p>

<p><strong>Rangorde:</strong> bij strijdigheid tussen deze overeenkomst en de NLdigital Voorwaarden 2025 gaan de specifieke afspraken in deze overeenkomst voor, conform artikel 1.2 van de NLdigital Voorwaarden 2025.</p>

{{-- ═══════════════════════════════════════════════════════════════════
     ARTIKEL 4 — HARDWARE KEUZE INFRASTRUCTUUR
═══════════════════════════════════════════════════════════════════════ --}}
<div class="page-break"></div>
<h2>Artikel 4 &mdash; Hardware keuze infrastructuur</h2>

@php
    $isA = $hwOptionA !== null;
    $isB = $hwOptionB !== null;
@endphp

{{-- Optie A --}}
<div class="option-block {{ $isA ? 'chosen' : '' }}">
    <div class="option-title">
        @if($isA) <span class="checkmark">&#10003;</span> @endif
        <span class="{{ $isA ? '' : 'not-chosen' }}">Optie A &mdash; Standaard server (Tower, RAID&nbsp;1) &mdash; &euro;&nbsp;3.500,-</span>
    </div>
    <ul class="{{ $isA ? '' : 'not-chosen' }}">
        <li>Tower-server met RAID&nbsp;1-configuratie (gespiegelde schijven)</li>
        <li>Firewall en switch voor een gesloten netwerk</li>
        <li>Bescherming tegen uitval van één harde schijf</li>
        <li>Let op: bij vervanging van een defecte schijf moet het systeem worden uitgeschakeld.</li>
    </ul>
</div>

{{-- Optie B --}}
<div class="option-block {{ $isB ? 'chosen' : '' }}">
    <div class="option-title">
        @if($isB) <span class="checkmark">&#10003;</span> @endif
        <span class="{{ $isB ? '' : 'not-chosen' }}">Optie B &mdash; High Availability cluster (3&nbsp;nodes) &mdash; &euro;&nbsp;9.500,-</span>
    </div>
    <ul class="{{ $isB ? '' : 'not-chosen' }}">
        <li>Cluster van 3 nodes (NUC&apos;s of vergelijkbaar) met gesynchroniseerde data</li>
        <li>Firewall en switch voor een gesloten netwerk</li>
        <li>Eén node kan volledig uitvallen zonder gevolgen voor de beschikbaarheid</li>
        <li>Vervanging van een defecte node kan hot-swap, zonder het systeem uit te schakelen</li>
        <li>Aanbevolen voor de hoogst haalbare beschikbaarheid en de kortste hersteltijd.</li>
    </ul>
</div>

{{-- UPS add-on --}}
@if($upsItem)
<div class="info-box">
    <strong>UPS &mdash; &euro;&nbsp;860,-</strong><br>
    De UPS vangt kortdurende stroomstoringen en -pieken op en zorgt voor een gecontroleerde, veilige afsluiting van het cluster bij langdurige stroomuitval.
</div>
@endif

{{-- Wifi / camera add-ons --}}
@if($addonItems->isNotEmpty())
<h3>Add-ons</h3>
<table>
    <thead><tr><th>Add-on</th><th>Toelichting</th><th class="text-right">Prijs</th></tr></thead>
    <tbody>
        @foreach($addonItems as $item)
        <tr>
            <td>{{ $item->product->name }}@if($item->quantity > 1) &times;&nbsp;{{ $item->quantity }}@endif</td>
            <td style="font-size:8.5pt; color:#555;">{{ Str::limit($item->product->description, 80) }}</td>
            <td class="text-right">
                {{ $item->product->is_price_on_quote ? 'Op offerte' : '&euro;&nbsp;'.number_format($item->unit_price_snapshot, 2, ',', '.') }}
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif

{{-- ═══════════════════════════════════════════════════════════════════
     ARTIKEL 5 — INSTALLATIEKOSTEN
═══════════════════════════════════════════════════════════════════════ --}}
<h2>Artikel 5 &mdash; Installatiekosten</h2>

@if($installItems->isNotEmpty())
<table>
    <thead><tr><th>Omschrijving</th><th class="text-right">Prijs</th></tr></thead>
    <tbody>
        @foreach($installItems as $item)
        <tr>
            <td>{{ $item->product->name }}@if($item->quantity > 1) &times;&nbsp;{{ $item->quantity }}@endif</td>
            <td class="text-right">&euro;&nbsp;{{ number_format($item->unit_price_snapshot * $item->quantity, 2, ',', '.') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif

<p>Benodigd installatiemateriaal wordt op basis van calculatie in rekening gebracht, afhankelijk van de daadwerkelijke situatie op locatie.</p>

{{-- ═══════════════════════════════════════════════════════════════════
     ARTIKEL 6 — SERVICECONTRACT
═══════════════════════════════════════════════════════════════════════ --}}
<div class="page-break"></div>
<h2>Artikel 6 &mdash; Servicecontract</h2>

@php
    $isStandaard = $svcItem && str_contains($svcItem->product->name, 'Standaard');
    $isPremium   = $svcItem && str_contains($svcItem->product->name, 'Premium');
@endphp

{{-- Standaard --}}
<div class="option-block {{ $isStandaard ? 'chosen' : '' }}">
    <div class="option-title">
        @if($isStandaard) <span class="checkmark">&#10003;</span> @endif
        <span class="{{ $isStandaard ? '' : 'not-chosen' }}">Optie 1 &mdash; Standaard &mdash; &euro;&nbsp;8.799,- / jaar</span>
    </div>
    <ul class="{{ $isStandaard ? '' : 'not-chosen' }}">
        <li>Ingecalculeerde support-inzet van circa 3 uur per maand</li>
        <li>3 dagen per jaar voor onderhoud en/of werkzaamheden op locatie</li>
        <li>Cloud backup van de configuratie, periodiek getest op terugzetbaarheid</li>
        <li>RMM (Remote Monitoring &amp; Management) &mdash; proactieve monitoring</li>
        <li>365 dagen per jaar bereikbaar, inclusief weekenden en feestdagen</li>
        <li>Responstijd: eerste reactie binnen 4 uur na melding, 365 dagen per jaar</li>
        <li>Hersteltijd: storing verholpen binnen 24 uur</li>
    </ul>
</div>

{{-- Premium --}}
<div class="option-block {{ $isPremium ? 'chosen' : '' }}">
    <div class="option-title">
        @if($isPremium) <span class="checkmark">&#10003;</span> @endif
        <span class="{{ $isPremium ? '' : 'not-chosen' }}">Optie 2 &mdash; Premium &mdash; &euro;&nbsp;13.764,- / jaar</span>
    </div>
    <ul class="{{ $isPremium ? '' : 'not-chosen' }}">
        <li>Ingecalculeerde support-inzet van circa 4 uur per maand</li>
        <li>4 dagen per jaar voor onderhoud en/of werkzaamheden op locatie</li>
        <li>Cloud backup van de configuratie, periodiek getest op terugzetbaarheid</li>
        <li>RMM (Remote Monitoring &amp; Management) &mdash; proactieve monitoring</li>
        <li>365 dagen per jaar bereikbaar, inclusief weekenden en feestdagen</li>
        <li>Responstijd: eerste reactie binnen 2 uur na melding, 365 dagen per jaar</li>
        <li>Hersteltijd: storing verholpen binnen 12 uur</li>
    </ul>
</div>

<h3>Afbakening servicecontract</h3>
<p>Het servicecontract heeft uitsluitend betrekking op de hardware-infrastructuur die door Proud Innovations B.V. is geleverd en geïnstalleerd. Storingen of problemen aan het kassasysteem zelf (software, configuratie, updates) vallen buiten dit contract en worden afgehandeld door Kassacentrum. Proud Innovations B.V. werkt indien nodig samen met Kassacentrum om te bepalen of een storing hardware- of softwarematig van aard is.</p>

<p>Vervanging van hardware-onderdelen die door normale slijtage of defect uitvallen is inbegrepen in het servicecontract, tenzij schade het gevolg is van externe factoren buiten de invloedssfeer van Proud Innovations B.V. (zoals waterschade, brand, blikseminslag, of vandalisme). In dat geval worden kosten op basis van nacalculatie in rekening gebracht.</p>

{{-- ═══════════════════════════════════════════════════════════════════
     ARTIKEL 7 — BETAALVOORWAARDEN
═══════════════════════════════════════════════════════════════════════ --}}
<h2>Artikel 7 &mdash; Betaalvoorwaarden</h2>

<table>
    <thead><tr><th>Moment</th><th>Betaling</th></tr></thead>
    <tbody>
        <tr>
            <td>Bij start project</td>
            <td>Volledige betaling hardware incl. add-ons en installatiekosten</td>
        </tr>
        <tr>
            <td>Bij oplevering</td>
            <td>Betaling eerste jaar servicecontract (vooruitbetaald)</td>
        </tr>
        <tr>
            <td>Jaarlijks vanaf jaar&nbsp;2</td>
            <td>Servicecontract jaarlijks voorafgaand vooruitbetaald</td>
        </tr>
    </tbody>
</table>

<p>Alle genoemde prijzen zijn exclusief btw. Betaling vindt plaats op basis van factuur, binnen de daarop vermelde betalingstermijn.</p>

{{-- ═══════════════════════════════════════════════════════════════════
     ARTIKEL 8 — LOOPTIJD EN OPZEGGING
═══════════════════════════════════════════════════════════════════════ --}}
<h2>Artikel 8 &mdash; Looptijd en opzegging servicecontract</h2>

<p>Het servicecontract gaat in op de datum van oplevering van de hardware-installatie en wordt aangegaan voor een initiële periode van <strong>één (1) jaar</strong>. Na afloop van de initiële periode wordt het contract jaarlijks automatisch verlengd voor een nieuwe periode van één jaar, tenzij één der partijen het contract schriftelijk opzegt met inachtneming van een opzegtermijn van <strong>drie (3) kalendermaanden</strong> vóór het verstrijken van de lopende contractperiode.</p>

<p>Opzegging dient schriftelijk (per aangetekende brief of per e-mail met ontvangstbevestiging) te worden gedaan. Bij tussentijdse beëindiging om andere redenen dan wanprestatie is de opdrachtgever gehouden de resterende contractwaarde van de lopende periode te voldoen.</p>

{{-- ═══════════════════════════════════════════════════════════════════
     ARTIKEL 9 — SPECIFIEKE AFSPRAKEN
═══════════════════════════════════════════════════════════════════════ --}}
<div class="page-break"></div>
<h2>Artikel 9 &mdash; Specifieke afspraken</h2>

<h3>9.1 Eigendom hardware</h3>
<p>De hardware blijft eigendom van Proud Innovations B.V. totdat de volledige koopprijs is voldaan. Na volledige betaling gaat het eigendom over op de Opdrachtgever. Het risico van verlies of beschadiging gaat over op de Opdrachtgever op het moment van feitelijke aflevering en installatie op locatie.</p>

<h3>9.2 Toegang locatie</h3>
<p>Opdrachtgever draagt er zorg voor dat Proud Innovations B.V. en haar medewerkers op de overeengekomen tijdstippen vrije en veilige toegang hebben tot de locatie en tot de relevante systemen, zodat installatie, onderhoud en storingsherstel kunnen plaatsvinden. Vertraging als gevolg van het niet beschikbaar zijn van toegang of medewerking komt voor rekening van de Opdrachtgever.</p>

<h3>9.3 Wijzigingen aan de infrastructuur</h3>
<p>Opdrachtgever zal zonder voorafgaande schriftelijke toestemming van Proud Innovations B.V. geen wijzigingen aanbrengen aan de geleverde hardware-infrastructuur, noch derden toegang verlenen tot de apparatuur voor werkzaamheden aan de hardware. Niet-geautoriseerde wijzigingen kunnen leiden tot vervallen van garantie en uitsluiting van servicecontractdekking voor de betreffende componenten.</p>

<h3>9.4 Geheimhouding</h3>
<p>Beide partijen verplichten zich tot geheimhouding van alle vertrouwelijke informatie die zij in het kader van deze overeenkomst over en weer ontvangen. Informatie wordt als vertrouwelijk beschouwd indien dit door de verstrekkende partij is aangegeven of als de ontvangende partij redelijkerwijs kan vermoeden dat het om vertrouwelijke informatie gaat. Deze geheimhoudingsverplichting geldt ook na beëindiging van de overeenkomst.</p>

<h3>9.5 Overmacht</h3>
<p>Geen der partijen is gehouden tot nakoming van enige verplichting indien zij daartoe verhinderd is als gevolg van overmacht. Onder overmacht wordt in ieder geval verstaan: storingen in netwerken of telecommunicatieverbindingen die buiten de invloedssfeer van partijen vallen, stakingen, brand, overstroming, extreme weersomstandigheden, pandemieën, en overheidsmaatregelen. Bij een overmachtsituatie die langer duurt dan 30 kalenderdagen, hebben beide partijen het recht de overeenkomst te ontbinden zonder verplichting tot schadevergoeding.</p>

<h3>9.6 Toepasselijk recht en geschillen</h3>
<p>Op deze overeenkomst is Nederlands recht van toepassing. Geschillen die voortvloeien uit of verband houden met deze overeenkomst zullen in eerste instantie worden voorgelegd aan de bevoegde rechter in het arrondissement Den Haag, tenzij partijen er schriftelijk voor kiezen het geschil aan arbitrage te onderwerpen.</p>

<h3>9.7 Wijziging en aanvulling</h3>
<p>Wijzigingen van of aanvullingen op deze overeenkomst zijn slechts geldig voor zover zij schriftelijk zijn overeengekomen en door beide partijen zijn ondertekend. Mondelinge afspraken, toezeggingen of mededelingen binden partijen niet, tenzij en voor zover zij schriftelijk zijn bevestigd.</p>

{{-- ═══════════════════════════════════════════════════════════════════
     ARTIKEL 10 — SAMENVATTING GEKOZEN CONFIGURATIE
═══════════════════════════════════════════════════════════════════════ --}}
<div class="page-break"></div>
<h2>Artikel 10 &mdash; Samenvatting gekozen configuratie</h2>

<table>
    <thead>
        <tr><th>Onderdeel</th><th class="text-right">Prijs (excl. BTW)</th></tr>
    </thead>
    <tbody>
        {{-- Gekozen hardware optie --}}
        @if($chosenHw)
        <tr>
            <td>Hardware &mdash; {{ $chosenHw->product->name }}</td>
            <td class="text-right">&euro;&nbsp;{{ number_format($chosenHw->unit_price_snapshot, 2, ',', '.') }}</td>
        </tr>
        @endif

        {{-- UPS --}}
        @if($upsItem)
        <tr>
            <td>UPS</td>
            <td class="text-right">&euro;&nbsp;860,00</td>
        </tr>
        @endif

        {{-- Add-ons --}}
        @foreach($addonItems as $item)
        <tr>
            <td>{{ $item->product->name }}@if($item->quantity > 1) &times;&nbsp;{{ $item->quantity }}@endif</td>
            <td class="text-right">
                {{ $item->product->is_price_on_quote ? 'Op offerte' : '&euro;&nbsp;'.number_format($item->unit_price_snapshot * $item->quantity, 2, ',', '.') }}
            </td>
        </tr>
        @endforeach

        {{-- Installatie --}}
        @foreach($installItems as $item)
        <tr>
            <td>Installatie &mdash; {{ $item->product->name }}@if($item->quantity > 1) &times;&nbsp;{{ $item->quantity }}@endif</td>
            <td class="text-right">&euro;&nbsp;{{ number_format($item->unit_price_snapshot * $item->quantity, 2, ',', '.') }}</td>
        </tr>
        @endforeach

        <tr>
            <td>Installatiemateriaal</td>
            <td class="text-right" style="color:#888;">Op calculatie</td>
        </tr>

        {{-- Eenmalig totaal --}}
        <tr class="total">
            <td>Totaal eenmalig (excl. BTW)</td>
            <td class="text-right">&euro;&nbsp;{{ number_format($quote->total_onetime_excl_vat, 2, ',', '.') }}</td>
        </tr>

        <tr class="sep"><td colspan="2"></td></tr>

        {{-- Service --}}
        @if($svcItem)
        <tr>
            <td>Servicecontract &mdash; {{ $svcItem->product->name }}, per jaar</td>
            <td class="text-right">&euro;&nbsp;{{ number_format($svcItem->unit_price_snapshot, 2, ',', '.') }}</td>
        </tr>
        @endif

        {{-- Jaarlijks totaal --}}
        <tr class="total">
            <td>Totaal jaarlijks (excl. BTW)</td>
            <td class="text-right">&euro;&nbsp;{{ number_format($quote->total_yearly_excl_vat, 2, ',', '.') }}</td>
        </tr>
    </tbody>
</table>

<p style="font-size:8.5pt; color:#555;">Alle bedragen exclusief BTW ({{ number_format($settings['vat_percentage'], 0) }}%). De eenmalige kosten worden gefactureerd bij start van het project. Het servicecontract wordt bij oplevering vooruitbetaald.</p>

{{-- ═══════════════════════════════════════════════════════════════════
     ARTIKEL 11 — ONDERTEKENING
═══════════════════════════════════════════════════════════════════════ --}}
<div class="page-break"></div>
<h2>Artikel 11 &mdash; Ondertekening</h2>

<p>Aldus overeengekomen en in tweevoud opgemaakt en ondertekend.</p>

<table class="cols" style="margin-top:8mm;">
<tr>
    <td class="col-left">
        <p style="font-weight:bold; margin-bottom:3mm;">Namens {{ $settings['company_name'] }}</p>
        <p style="color:#555; font-size:9pt;">Naam en functie: {{ $settings['company_representative'] }}</p>
        <p style="color:#555; font-size:9pt; margin-bottom:8mm;">Datum en plaats: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;, {{ $settings['company_address'] }}</p>
        <p style="font-size:8.5pt; color:#888; margin-bottom:1mm;">Handtekening</p>
        <div class="sign-line"></div>
    </td>
    <td class="col-right">
        <p style="font-weight:bold; margin-bottom:3mm;">Namens Opdrachtgever</p>
        <p style="color:#555; font-size:9pt;">Naam en functie: {{ $quote->customer->contact_name }}</p>
        <p style="color:#555; font-size:9pt; margin-bottom:8mm;">Datum en plaats: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;, &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
        <p style="font-size:8.5pt; color:#888; margin-bottom:1mm;">Handtekening</p>
        <div class="sign-line"></div>
    </td>
</tr>
</table>

</body>
</html>
