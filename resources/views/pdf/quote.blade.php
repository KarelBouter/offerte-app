<!DOCTYPE html>
<html lang="nl">
<head>
<meta charset="UTF-8">
<style>
* { margin: 0; padding: 0; box-sizing: border-box; }
body {
    font-family: Arial, DejaVu Sans, sans-serif;
    font-size: 9pt;
    color: #1a1a1a;
    line-height: 1.5;
}
.titelblok { margin-bottom: 8mm; border-bottom: 2pt solid #1B3A6B; padding-bottom: 4mm; }
.titel-label { font-size: 7.5pt; color: #888888; text-transform: uppercase; letter-spacing: 1px; }
.titel-naam { font-size: 18pt; font-weight: bold; color: #1B3A6B; margin-top: 1mm; }
.titel-meta { font-size: 8.5pt; color: #555555; line-height: 1.8; text-align: right; vertical-align: bottom; }
.artikel { margin-bottom: 8mm; }
.artikel-nieuw { page-break-before: always; }
h2 { font-size: 11pt; font-weight: bold; color: #1B3A6B; border-bottom: 1pt solid #1B3A6B; padding-bottom: 2mm; margin-bottom: 4mm; }
h3 { font-size: 9pt; font-weight: bold; color: #333333; margin-bottom: 2mm; }
p { margin-bottom: 3mm; font-size: 9pt; }
.partijen-tabel { width: 100%; border-collapse: collapse; margin-bottom: 4mm; }
.partijen-tabel th { background-color: #1B3A6B; color: white; font-size: 9pt; padding: 3mm 4mm; text-align: left; width: 50%; }
.partijen-tabel td { padding: 2mm 4mm; font-size: 9pt; vertical-align: top; width: 50%; }
.optie-blok { border: 1pt solid #cccccc; padding: 4mm; margin-bottom: 4mm; background-color: #f8f8f8; }
.optie-blok.gekozen { border-color: #1B3A6B; background-color: #EEF4FF; }
.optie-titel { font-size: 9.5pt; font-weight: bold; color: #555555; margin-bottom: 2mm; }
.optie-blok.gekozen .optie-titel { color: #1B3A6B; }
.optie-blok ul { margin-left: 5mm; margin-top: 2mm; }
.optie-blok li { font-size: 9pt; margin-bottom: 1mm; }
.data-tabel { width: 100%; border-collapse: collapse; margin-bottom: 4mm; }
.data-tabel thead th { background-color: #1B3A6B; color: white; padding: 3mm 4mm; font-size: 9pt; text-align: left; }
.data-tabel thead th.rechts { text-align: right; }
.data-tabel tbody td { padding: 2.5mm 4mm; font-size: 9pt; border-bottom: 0.5pt solid #eeeeee; }
.data-tabel tbody td.rechts { text-align: right; }
.data-tabel tbody tr.totaal td { font-weight: bold; border-top: 1pt solid #1B3A6B; border-bottom: 1pt solid #1B3A6B; background-color: #f0f4ff; }
.data-tabel tbody tr.service-scheiding td { padding-top: 4mm; }
.ondertekening-tabel { width: 100%; border-collapse: collapse; margin-top: 8mm; }
.ondertekening-tabel td { width: 50%; padding: 2mm 4mm; vertical-align: top; }
.handtekening-lijn { border-top: 1pt solid #333333; margin-top: 20mm; width: 80%; }
.subartikel { margin-bottom: 4mm; }
.subartikel-titel { font-weight: bold; font-size: 9pt; color: #1B3A6B; margin-bottom: 1.5mm; }
.afbakening { margin-top: 4mm; padding: 3mm 4mm; background-color: #f8f8f8; border-left: 2pt solid #1B3A6B; font-size: 8.5pt; }
.gegenereerd { margin-top: 6mm; padding: 3mm 4mm; background-color: #f5f5f5; border: 0.5pt solid #cccccc; font-size: 7.5pt; color: #777777; }
</style>
</head>
<body>


<div class="titelblok">
    <div class="titel-label">Overeenkomst</div>
    <div class="titel-naam">Kassa Continuïteitsdienst</div>
</div>

<div class="artikel">
    <h2>Artikel 1 &mdash; Partijen</h2>
    <table class="partijen-tabel">
        <thead>
            <tr>
                <th>Opdrachtnemer (leverancier)</th>
                <th>Opdrachtgever (klant)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <b>Naam:</b> {{ $settings->get('company_name', 'Proud Innovations B.V.') }}<br>
                    <b>Vestigingsadres:</b> {{ $settings->get('company_address', 'Zoetermeer') }}<br>
                    <b>KvK-nummer:</b> {{ $settings->get('company_kvk', '') }}<br>
                    <b>Vertegenwoordigd door:</b> {{ $settings->get('company_representative', '') }}
                </td>
                <td>
                    <b>Bedrijfsnaam:</b> {{ $quote->customer->company_name }}<br>
                    <b>Vestigingsadres:</b> {{ $quote->customer->address }}<br>
                    <b>KvK-nummer:</b> {{ $quote->customer->kvk_number }}<br>
                    <b>Vertegenwoordigd door:</b> {{ $quote->customer->contact_name }}<br>
                    <b>Locatie installatie:</b> {{ $quote->installation_address ?? $quote->customer->address }}
                </td>
            </tr>
        </tbody>
    </table>
    <p>Partijen komen de hierna genoemde dienstverlening overeen, bestaande uit de levering en installatie van hardware (eenmalig) en een jaarlijks servicecontract (doorlopend), zoals hieronder per onderdeel vastgelegd.</p>
</div>

<div class="artikel">
    <h2>Artikel 2 &mdash; Omschrijving van de dienst</h2>
    <p>{!! nl2br(e($settings->get('pdf_tekst_artikel_2', ''))) !!}</p>
</div>

<div class="artikel">
    <h2>Artikel 3 &mdash; Toepasselijke voorwaarden</h2>
    <p>{!! nl2br(e($settings->get('pdf_tekst_artikel_3', ''))) !!}</p>
</div>

<div class="artikel artikel-nieuw">
    <h2>Artikel 4 &mdash; Hardware keuze infrastructuur</h2>
    @php
        $isOptieA = $quote->items->contains(fn($i) => str_contains($i->product->name ?? '', 'Optie A'));
        $isOptieB = $quote->items->contains(fn($i) => str_contains($i->product->name ?? '', 'Optie B'));
        $heeftUps = $quote->items->contains(fn($i) => $i->product->name === 'UPS');
        $wifiItems = $quote->items->filter(fn($i) => str_contains($i->product->name ?? '', 'Access Point'));
        $cameraItems = $quote->items->filter(fn($i) => str_contains(strtolower($i->product->name ?? ''), 'camera'));
    @endphp
    <div class="optie-blok {{ $isOptieA ? 'gekozen' : '' }}">
        <div class="optie-titel">{!! $isOptieA ? '&#10003; ' : '' !!}Optie A &mdash; Standaard server (Tower, RAID 1) &mdash; &euro; 3.500,-</div>
        <ul>
            <li>Tower-server met RAID 1-configuratie (gespiegelde schijven)</li>
            <li>Firewall en switch voor een gesloten netwerk</li>
            <li>Bescherming tegen uitval van &eacute;&eacute;n harde schijf</li>
            <li>Let op: bij vervanging van een defecte schijf moet het systeem worden uitgeschakeld.</li>
        </ul>
    </div>
    <div class="optie-blok {{ $isOptieB ? 'gekozen' : '' }}">
        <div class="optie-titel">{!! $isOptieB ? '&#10003; ' : '' !!}Optie B &mdash; High Availability cluster (3 nodes) &mdash; &euro; 9.500,-</div>
        <ul>
            <li>Cluster van 3 nodes (NUC's of vergelijkbaar) met gesynchroniseerde data</li>
            <li>Firewall en switch voor een gesloten netwerk</li>
            <li>E&eacute;n node kan volledig uitvallen zonder gevolgen voor de beschikbaarheid</li>
            <li>Vervanging van een defecte node kan hot-swap, zonder het systeem uit te schakelen</li>
            <li>Aanbevolen voor de hoogst haalbare beschikbaarheid en de kortste hersteltijd.</li>
        </ul>
    </div>
    @if($heeftUps)
    <div style="border: 1pt solid #cccccc; padding: 4mm; margin-bottom: 4mm; background-color: #f8f8f8;">
        <b>UPS &mdash; &euro; 860,-</b><br>
        De UPS vangt kortdurende stroomstoringen en -pieken op en zorgt voor een gecontroleerde, veilige afsluiting van het cluster bij langdurige stroomuitval.
    </div>
    @endif
    @if($wifiItems->count() > 0 || $cameraItems->count() > 0)
    <h3>Add-ons</h3>
    <table class="data-tabel">
        <thead><tr><th>Add-on</th><th>Toelichting</th><th class="rechts">Prijs</th></tr></thead>
        <tbody>
            @foreach($wifiItems as $item)
            <tr><td>{{ $item->product->name }} &times; {{ $item->quantity }}</td><td>{{ $item->product->description }}</td><td class="rechts">Op offerte</td></tr>
            @endforeach
            @foreach($cameraItems as $item)
            <tr><td>{{ $item->product->name }} &times; {{ $item->quantity }}</td><td>{{ $item->product->description }}</td><td class="rechts">Op offerte</td></tr>
            @endforeach
        </tbody>
    </table>
    @endif
</div>

<div class="artikel">
    <h2>Artikel 5 &mdash; Installatiekosten</h2>
    @php
        $installatieItems = $quote->items->filter(fn($i) =>
            str_contains(strtolower($i->product->category ?? ''), 'installatie') &&
            !str_contains(strtolower($i->product->name ?? ''), 'materiaal')
        );
    @endphp
    @if($installatieItems->isNotEmpty())
    <table class="data-tabel">
        <thead><tr><th>Omschrijving</th><th class="rechts">Prijs</th></tr></thead>
        <tbody>
            @foreach($installatieItems as $item)
            <tr>
                <td>{{ $item->product->name }}</td>
                <td class="rechts">
                    @if($item->product->is_price_on_quote) Op offerte
                    @else &euro; {{ number_format($item->unit_price_snapshot, 2, ',', '.') }}
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
    <p>{!! nl2br(e($settings->get('pdf_tekst_artikel_5', 'Benodigd installatiemateriaal wordt op basis van calculatie in rekening gebracht, afhankelijk van de daadwerkelijke situatie op locatie.'))) !!}</p>
</div>

<div class="artikel artikel-nieuw">
    <h2>Artikel 6 &mdash; Servicecontract</h2>
    @php
        $isStandaard = $quote->items->contains(fn($i) => str_contains($i->product->name ?? '', 'Standaard'));
        $isPremium   = $quote->items->contains(fn($i) => str_contains($i->product->name ?? '', 'Premium'));
    @endphp
    <div class="optie-blok {{ $isStandaard ? 'gekozen' : '' }}">
        <div class="optie-titel">{!! $isStandaard ? '&#10003; ' : '' !!}Optie 1 &mdash; Standaard &mdash; &euro; 8.799,- / jaar</div>
        <ul>
            <li>Ingecalculeerde support-inzet van circa 3 uur per maand</li>
            <li>3 dagen per jaar voor onderhoud en/of werkzaamheden op locatie</li>
            <li>Cloud backup van de configuratie, periodiek getest op terugzetbaarheid</li>
            <li>RMM (Remote Monitoring &amp; Management) &mdash; proactieve monitoring</li>
            <li>365 dagen per jaar bereikbaar, inclusief weekenden en feestdagen</li>
            <li>Responstijd: eerste reactie binnen 4 uur na melding, 365 dagen per jaar</li>
            <li>Hersteltijd: storing verholpen binnen 24 uur</li>
        </ul>
    </div>
    <div class="optie-blok {{ $isPremium ? 'gekozen' : '' }}">
        <div class="optie-titel">{!! $isPremium ? '&#10003; ' : '' !!}Optie 2 &mdash; Premium &mdash; &euro; 13.764,- / jaar</div>
        <ul>
            <li>Ingecalculeerde support-inzet van circa 4 uur per maand</li>
            <li>4 dagen per jaar voor onderhoud en/of werkzaamheden op locatie</li>
            <li>Cloud backup van de configuratie, periodiek getest op terugzetbaarheid</li>
            <li>RMM (Remote Monitoring &amp; Management) &mdash; proactieve monitoring</li>
            <li>365 dagen per jaar bereikbaar, inclusief weekenden en feestdagen</li>
            <li>Responstijd: eerste reactie binnen 2 uur na melding, 365 dagen per jaar</li>
            <li>Hersteltijd: storing verholpen binnen 12 uur</li>
        </ul>
    </div>
    <div class="afbakening">
        <b>Afbakening servicecontract</b><br><br>
        {!! nl2br(e($settings->get('pdf_tekst_afbakening_service', ''))) !!}
    </div>
</div>

<div class="artikel">
    <h2>Artikel 7 &mdash; Betaalvoorwaarden</h2>
    <table class="data-tabel">
        <thead><tr><th>Moment</th><th>Betaling</th></tr></thead>
        <tbody>
            <tr><td>Bij start project</td><td>Volledige betaling hardware incl. add-ons en installatiekosten</td></tr>
            <tr><td>Bij oplevering</td><td>Betaling eerste jaar servicecontract (vooruitbetaald)</td></tr>
            <tr><td>Jaarlijks vanaf jaar 2</td><td>Servicecontract jaarlijks voorafgaand vooruitbetaald</td></tr>
        </tbody>
    </table>
    <p>{!! nl2br(e($settings->get('pdf_tekst_artikel_7', ''))) !!}</p>
</div>

<div class="artikel">
    <h2>Artikel 8 &mdash; Looptijd en opzegging servicecontract</h2>
    <p>{!! nl2br(e($settings->get('pdf_tekst_artikel_8', ''))) !!}</p>
</div>

<div class="artikel artikel-nieuw">
    <h2>Artikel 9 &mdash; Specifieke afspraken</h2>
    <div class="subartikel">
        <div class="subartikel-titel">9.1 Eigendom hardware</div>
        <p>{!! nl2br(e($settings->get('pdf_tekst_artikel_9_1', ''))) !!}</p>
    </div>
    <div class="subartikel">
        <div class="subartikel-titel">9.2 Toegang locatie</div>
        <p>{!! nl2br(e($settings->get('pdf_tekst_artikel_9_2', ''))) !!}</p>
    </div>
    <div class="subartikel">
        <div class="subartikel-titel">9.3 Wijzigingen aan de infrastructuur</div>
        <p>{!! nl2br(e($settings->get('pdf_tekst_artikel_9_3', ''))) !!}</p>
    </div>
    <div class="subartikel">
        <div class="subartikel-titel">9.4 Geheimhouding</div>
        <p>{!! nl2br(e($settings->get('pdf_tekst_artikel_9_4', ''))) !!}</p>
    </div>
    <div class="subartikel">
        <div class="subartikel-titel">9.5 Overmacht</div>
        <p>{!! nl2br(e($settings->get('pdf_tekst_artikel_9_5', ''))) !!}</p>
    </div>
    <div class="subartikel">
        <div class="subartikel-titel">9.6 Toepasselijk recht en geschillen</div>
        <p>{!! nl2br(e($settings->get('pdf_tekst_artikel_9_6', ''))) !!}</p>
    </div>
    <div class="subartikel">
        <div class="subartikel-titel">9.7 Wijziging en aanvulling</div>
        <p>{!! nl2br(e($settings->get('pdf_tekst_artikel_9_7', ''))) !!}</p>
    </div>
</div>

<div class="artikel artikel-nieuw">
    <h2>Artikel 10 &mdash; Samenvatting gekozen configuratie</h2>
    <table class="data-tabel">
        <thead><tr><th>Onderdeel</th><th class="rechts">Prijs (excl. BTW)</th></tr></thead>
        <tbody>
            @foreach($quote->items as $item)
                @php $naam = strtolower($item->product->name ?? ''); @endphp
                @if(!str_contains($naam, 'switch standaard') && !str_contains($naam, 'firewall') && !str_contains($naam, 'nuc node') && !str_contains($naam, 'ssd') && !str_contains(strtolower($item->product->category ?? ''), 'service'))
                <tr>
                    <td>
                        @if(str_contains($item->product->category ?? '', 'Installatie')) Installatie &mdash; @endif
                        {{ $item->product->name }}
                        @if($item->quantity > 1 && !str_contains($item->product->category ?? '', 'Service')) &times; {{ $item->quantity }} @endif
                        @if(str_contains($item->product->category ?? '', 'Service')) , per jaar @endif
                    </td>
                    <td class="rechts">
                        @if($item->product->is_price_on_quote)
                            {{ str_contains($naam, 'materiaal') ? 'Op calculatie' : 'Op offerte' }}
                        @else
                            &euro; {{ number_format($item->unit_price_snapshot * $item->quantity, 2, ',', '.') }}
                        @endif
                    </td>
                </tr>
                @endif
            @endforeach
            <tr class="totaal">
                <td>Totaal eenmalig (excl. BTW)</td>
                <td class="rechts">&euro; {{ number_format($quote->total_onetime_excl_vat, 2, ',', '.') }}</td>
            </tr>
            <tr class="service-scheiding"><td colspan="2"></td></tr>
            @php $serviceItem = $quote->items->first(fn($i) => str_contains($i->product->category ?? '', 'Service')); @endphp
            @if($serviceItem)
            <tr>
                <td>Servicecontract &mdash; {{ $serviceItem->product->name }}, per jaar</td>
                <td class="rechts">&euro; {{ number_format($serviceItem->unit_price_snapshot, 2, ',', '.') }}</td>
            </tr>
            @endif
            <tr class="totaal">
                <td>Totaal jaarlijks (excl. BTW)</td>
                <td class="rechts">&euro; {{ number_format($quote->total_yearly_excl_vat, 2, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>
    <p>{!! nl2br(e($settings->get('pdf_tekst_artikel_10_footer', ''))) !!}</p>
</div>

<div class="artikel artikel-nieuw">
    <h2>Artikel 11 &mdash; Ondertekening</h2>
    <p>Beide partijen verklaren kennis te hebben genomen van de inhoud van deze overeenkomst en gaan hiermee akkoord.</p>
    <table class="ondertekening-tabel">
        <tr>
            <td>
                <b>Namens {{ $settings->get('company_name', 'Proud Innovations B.V.') }}</b><br><br>
                {{ $settings->get('company_representative', '') }}<br><br><br><br><br>
                <div class="handtekening-lijn"></div>
                Handtekening<br><br>
                Datum: ________________________________<br><br>
                Plaats: {{ $settings->get('company_address', 'Zoetermeer') }}
            </td>
            <td>
                <b>Namens Opdrachtgever</b><br><br>
                {{ $quote->customer->contact_name }}<br><br>
                @if(!empty($signatureSrc))
                    <img src="file://{{ $signatureSrc }}" style="max-height: 30mm; max-width: 80mm; margin-bottom: 2mm;"><br>
                @else
                    <br><br><br><br>
                @endif
                <div class="handtekening-lijn"></div>
                @if($quote->signed_at)
                    Ondertekend op: {{ $quote->signed_at->format('d-m-Y H:i') }}<br>
                    Door: {{ $quote->signed_by_name }}<br>
                @else
                    Handtekening<br><br>
                    Datum: ________________________________<br><br>
                @endif
                Bedrijf: {{ $quote->customer->company_name }}
            </td>
        </tr>
    </table>
    <div class="gegenereerd" style="margin-top: 10mm;">
        Dit document is gegenereerd via het offertesysteem van {{ $settings->get('company_name', 'Proud Innovations B.V.') }} op {{ now()->format('d-m-Y H:i') }}. Offertenummer: {{ $quote->quote_number }}.
    </div>
</div>

</body>
</html>
