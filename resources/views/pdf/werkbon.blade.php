<!DOCTYPE html>
<html lang="nl">
<head>
<meta charset="UTF-8">
<style>
* { margin: 0; padding: 0; box-sizing: border-box; }
body {
    font-family: DejaVu Sans, Arial, sans-serif;
    font-size: 10pt;
    color: #1a1a1a;
    line-height: 1.5;
}
h1 {
    font-size: 15pt;
    color: #1B3A6B;
    margin-bottom: 6mm;
    padding-bottom: 2mm;
    border-bottom: 1pt solid #1B3A6B;
}
h2 {
    font-size: 11pt;
    color: #1B3A6B;
    margin-top: 6mm;
    margin-bottom: 3mm;
}
.klant-blok {
    background: #f7f8fa;
    border: 1pt solid #d0d5e0;
    border-radius: 3pt;
    padding: 4mm 5mm;
    margin-bottom: 6mm;
}
.klant-blok table { width: 100%; border-collapse: collapse; }
.klant-blok td { padding: 1mm 2mm; font-size: 9pt; vertical-align: top; }
.klant-blok .label { color: #666; width: 35mm; }

/* ── Checklist items ─────────────────────────────── */
.checklist { margin-bottom: 4mm; }
.checklist-item {
    margin-bottom: 4mm;
    page-break-inside: avoid;
    border: 1pt solid #d0d5e0;
    border-radius: 3pt;
}
.checklist-item-header {
    background: #1B3A6B;
    color: white;
    padding: 2mm 4mm;
    font-size: 9.5pt;
    font-weight: bold;
    display: table;
    width: 100%;
    border-radius: 2pt 2pt 0 0;
}
.checklist-item-header .hdr-checkbox {
    display: table-cell;
    width: 8mm;
    vertical-align: middle;
}
.checkbox-box {
    width: 4mm;
    height: 4mm;
    border: 1.5pt solid rgba(255,255,255,0.8);
    display: inline-block;
    background: transparent;
    border-radius: 1pt;
}
.checkbox-box-dark {
    width: 4mm;
    height: 4mm;
    border: 1pt solid #555;
    display: inline-block;
    border-radius: 1pt;
}
.checklist-item-header .hdr-naam {
    display: table-cell;
    vertical-align: middle;
}
.checklist-item-header .hdr-qty {
    display: table-cell;
    width: 18mm;
    vertical-align: middle;
    text-align: right;
    font-size: 8.5pt;
    font-weight: normal;
    opacity: 0.85;
}
.aantekening-badge {
    display: inline-block;
    background: #f59e0b;
    color: #1a1a1a;
    font-size: 7.5pt;
    font-weight: bold;
    padding: 0.5mm 2.5mm;
    border-radius: 2pt;
    letter-spacing: 0.3pt;
    text-transform: uppercase;
    vertical-align: middle;
}
.aantekening-blok {
    background: #fffbeb;
    border: 1pt solid #f59e0b;
    border-radius: 2pt;
    padding: 2mm 3mm;
    margin-bottom: 2mm;
    font-size: 9pt;
    color: #92400e;
}
.aantekening-blok strong { font-size: 8.5pt; text-transform: uppercase; color: #78350f; }
.checklist-item-body {
    padding: 3mm 4mm;
}
.instructie {
    font-size: 9pt;
    color: #333;
    margin-bottom: 2mm;
    white-space: pre-line;
}
.notitie {
    font-size: 9pt;
    color: #7c4700;
    background: #fff8ee;
    border-left: 2.5pt solid #e08a00;
    padding: 1.5mm 3mm;
    margin-bottom: 2mm;
    border-radius: 0 2pt 2pt 0;
}
.notitie strong { font-size: 8.5pt; text-transform: uppercase; color: #b36800; }

/* ── Kabelruns tabel ─────────────────────────────── */
.runs-tabel {
    width: 100%;
    border-collapse: collapse;
    margin-top: 2mm;
}
.runs-tabel th {
    background: #eef1f7;
    padding: 1.5mm 3mm;
    font-size: 8.5pt;
    text-align: left;
    border-bottom: 1pt solid #c8cdd8;
    color: #444;
    font-weight: bold;
}
.runs-tabel td {
    padding: 2mm 3mm;
    font-size: 9pt;
    border-bottom: 1pt solid #e8eaf0;
    vertical-align: middle;
}
.runs-tabel tr:last-child td { border-bottom: none; }
.col-num { width: 10mm; color: #888; text-align: center; }
.col-meters { width: 16mm; text-align: right; font-weight: bold; color: #1B3A6B; }
.col-afg { width: 18mm; text-align: center; }
.totaal-rij td {
    background: #f0f3f9;
    font-weight: bold;
    border-top: 1.5pt solid #1B3A6B;
    font-size: 9pt;
}

/* ── Opmerkingen / handtekening ─────────────────── */
.opmerkingen {
    margin-top: 8mm;
    page-break-inside: avoid;
}
.handtekening {
    margin-top: 10mm;
    page-break-inside: avoid;
}
.handtekening table { width: 100%; border-collapse: collapse; }
.handtekening td { width: 50%; vertical-align: bottom; padding-right: 10mm; }
.handtekening .lijn {
    border-bottom: 1pt solid #555;
    margin-top: 12mm;
    margin-bottom: 1mm;
}
.handtekening .label { font-size: 8pt; color: #666; }
</style>
</head>
<body>

<h1>Werkbon installatie</h1>

{{-- Klantgegevens --}}
<div class="klant-blok">
    <table>
        <tr>
            <td class="label">Klant</td>
            <td><strong>{{ $quote->customer->company_name }}</strong></td>
            <td class="label">Offertenummer</td>
            <td><strong>{{ $quote->quote_number }}</strong></td>
        </tr>
        <tr>
            <td class="label">Adres (klant)</td>
            <td>{{ $quote->customer->address }}</td>
            <td class="label">Datum</td>
            <td>{{ \Carbon\Carbon::parse($quote->created_at)->format('d-m-Y') }}</td>
        </tr>
        @if($quote->installation_address)
        <tr>
            <td class="label">Installatieadres</td>
            <td colspan="3"><strong>{{ $quote->installation_address }}</strong></td>
        </tr>
        @endif
        <tr>
            <td class="label">Contactpersoon</td>
            <td>{{ $quote->customer->contact_name }}</td>
            <td class="label">Telefoon</td>
            <td>{{ $quote->customer->contact_phone ?? '—' }}</td>
        </tr>
    </table>
</div>

{{-- Installatiechecklist --}}
<h2>Installatiechecklist</h2>

<div class="checklist">
@forelse($allItems as $item)
@php
    $product       = $item->product;
    $isCable       = (bool) $product->price_per_meter;
    $runs          = $item->cable_runs ?? [];
    $hasRuns       = $isCable && count(array_filter($runs, fn($r) => (int)(is_array($r) ? ($r['meters'] ?? 0) : $r) > 0)) > 0;
    $totalMeters   = $hasRuns
        ? (int) array_sum(array_map(fn($r) => is_array($r) ? (int)($r['meters'] ?? 0) : (int)$r, $runs))
        : 0;
    $aantalRuns    = $hasRuns
        ? count(array_filter($runs, fn($r) => (int)(is_array($r) ? ($r['meters'] ?? 0) : $r) > 0))
        : 0;
    $hasInstructie = !empty($product->installatie_instructie);
    $hasNotitie    = !empty($item->installatie_notitie);
    $aantekening   = $item->werkbon_aantekening ?? null;
    $aantekLabel   = $aantekening ? (\App\Support\WerkbonAantekeningen::OPTIES[$aantekening] ?? $aantekening) : null;
    $aantekKort    = $aantekening ? (\App\Support\WerkbonAantekeningen::KORT[$aantekening]  ?? '??') : null;
    $hasBody       = $hasInstructie || $hasNotitie || $hasRuns || $aantekening;
    $qty           = $item->quantity;
@endphp
<div class="checklist-item">
    <div class="checklist-item-header">
        <div class="hdr-checkbox">
            @if($aantekening)
                <div class="aantekening-badge">{{ $aantekKort }}</div>
            @else
                <div class="checkbox-box"></div>
            @endif
        </div>
        <div class="hdr-naam">{{ $product->name }}</div>
        <div class="hdr-qty">
            @if($isCable && $aantalRuns > 0)
                {{ $aantalRuns }} run{{ $aantalRuns !== 1 ? 's' : '' }}, {{ $totalMeters }}m
            @else
                {{ $qty }}×
            @endif
        </div>
    </div>

    @if($hasBody)
    <div class="checklist-item-body">
        @if($aantekening)
        <div class="aantekening-blok"><strong>{{ $aantekLabel }}</strong></div>
        @endif

        @if($hasInstructie)
        <div class="instructie">{{ $product->installatie_instructie }}</div>
        @endif

        @if($hasNotitie)
        <div class="notitie"><strong>Let op:</strong> {{ $item->installatie_notitie }}</div>
        @endif

        @if($hasRuns)
        <table class="runs-tabel">
            <thead>
                <tr>
                    <th class="col-num">#</th>
                    <th>Omschrijving / route</th>
                    <th class="col-meters">Meters</th>
                    <th class="col-afg">Afgetekend</th>
                </tr>
            </thead>
            <tbody>
                @foreach($runs as $i => $run)
                @php
                    $m = is_array($run) ? (int)($run['meters'] ?? 0) : (int)$run;
                    $n = is_array($run) ? ($run['naam'] ?? '') : '';
                @endphp
                @if($m > 0)
                <tr>
                    <td class="col-num">{{ $i + 1 }}</td>
                    <td>{{ $n ?: '—' }}</td>
                    <td class="col-meters">{{ $m }}m</td>
                    <td class="col-afg"><div class="checkbox-box-dark"></div></td>
                </tr>
                @endif
                @endforeach
                <tr class="totaal-rij">
                    <td colspan="2" style="padding-left:3mm;">Totaal {{ $aantalRuns }} {{ $aantalRuns === 1 ? 'run' : 'runs' }}</td>
                    <td class="col-meters">{{ $totalMeters }}m</td>
                    <td></td>
                </tr>
            </tbody>
        </table>
        @endif
    </div>
    @endif
</div>
@empty
    <p style="color:#888; font-style:italic;">Geen producten gevonden in deze offerte.</p>
@endforelse
</div>

{{-- Opmerkingen installateur --}}
<div class="opmerkingen">
    <h2>Opmerkingen installateur</h2>
    <div style="border: 1pt solid #c8cdd8; border-radius: 2pt; min-height: 25mm; padding: 3mm 4mm;">&nbsp;</div>
</div>

{{-- Handtekening --}}
<div class="handtekening">
    <table>
        <tr>
            <td>
                <div class="lijn"></div>
                <div class="label">Handtekening installateur &nbsp;&mdash;&nbsp; naam + datum</div>
            </td>
            <td>
                <div class="lijn"></div>
                <div class="label">Handtekening klant (akkoord oplevering) &nbsp;&mdash;&nbsp; naam + datum</div>
            </td>
        </tr>
    </table>
</div>

</body>
</html>
