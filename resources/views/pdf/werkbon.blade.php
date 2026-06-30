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
.kabelproduct {
    margin-bottom: 6mm;
    page-break-inside: avoid;
}
.kabelproduct-header {
    background: #1B3A6B;
    color: white;
    padding: 2mm 4mm;
    font-size: 10pt;
    font-weight: bold;
    border-radius: 2pt 2pt 0 0;
}
.runs-tabel {
    width: 100%;
    border-collapse: collapse;
    border: 1pt solid #c8cdd8;
    border-top: none;
}
.runs-tabel th {
    background: #eef1f7;
    padding: 2mm 4mm;
    font-size: 8.5pt;
    text-align: left;
    border-bottom: 1pt solid #c8cdd8;
    color: #444;
    font-weight: bold;
}
.runs-tabel td {
    padding: 2.5mm 4mm;
    font-size: 9.5pt;
    border-bottom: 1pt solid #e8eaf0;
    vertical-align: middle;
}
.runs-tabel tr:last-child td { border-bottom: none; }
.runs-tabel .col-num { width: 12mm; color: #888; text-align: center; }
.runs-tabel .col-meters { width: 18mm; text-align: right; font-weight: bold; color: #1B3A6B; }
.runs-tabel .col-naam { }
.totaal-rij td {
    background: #f0f3f9;
    font-weight: bold;
    border-top: 1.5pt solid #1B3A6B;
    font-size: 9.5pt;
}
.geen-runs {
    color: #888;
    font-style: italic;
    font-size: 9pt;
    padding: 3mm 4mm;
    border: 1pt solid #c8cdd8;
    border-top: none;
}
.checkboxes {
    margin-top: 8mm;
    page-break-inside: avoid;
}
.checkboxes h2 { margin-top: 0; }
.checkbox-rij {
    display: table;
    width: 100%;
    margin-bottom: 3mm;
}
.checkbox-cel {
    display: table-cell;
    width: 6mm;
    vertical-align: top;
    padding-top: 0.5mm;
}
.checkbox-box {
    width: 4mm;
    height: 4mm;
    border: 1pt solid #555;
    display: inline-block;
}
.checkbox-tekst {
    display: table-cell;
    vertical-align: top;
    padding-left: 2mm;
    font-size: 9.5pt;
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

<h1>Werkbon kabelinstallatie</h1>

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

{{-- Kabelruns per product --}}
@if($cableItems->isEmpty())
    <p style="color:#888; font-style:italic;">Geen kabelproducten gevonden in deze offerte.</p>
@else
    @foreach($cableItems as $item)
    @php
        $runs = $item->cable_runs ?? [];
        $totalMeters = (int) array_sum(array_map(
            fn($r) => is_array($r) ? (int)($r['meters'] ?? 0) : (int)$r,
            $runs
        ));
        $aantalRuns = count(array_filter($runs, fn($r) => (int)(is_array($r) ? ($r['meters'] ?? 0) : $r) > 0));
    @endphp
    <div class="kabelproduct">
        <div class="kabelproduct-header">
            {{ $item->product->name }}
            @if($item->product->description)
                <span style="font-weight:normal; font-size:8.5pt; opacity:0.85;"> &mdash; {{ $item->product->description }}</span>
            @endif
        </div>

        @if(empty($runs))
            <div class="geen-runs">Geen kabelrun-details beschikbaar voor dit product.</div>
        @else
            <table class="runs-tabel">
                <thead>
                    <tr>
                        <th class="col-num">#</th>
                        <th class="col-naam">Omschrijving / route</th>
                        <th class="col-meters">Meters</th>
                        <th style="width:20mm; text-align:center;">Afgetekend</th>
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
                        <td class="col-naam">{{ $n ?: '—' }}</td>
                        <td class="col-meters">{{ $m }}m</td>
                        <td style="text-align:center;">
                            <div class="checkbox-box"></div>
                        </td>
                    </tr>
                    @endif
                    @endforeach
                    <tr class="totaal-rij">
                        <td colspan="2" style="padding-left:4mm;">Totaal {{ $aantalRuns }} {{ $aantalRuns === 1 ? 'run' : 'runs' }}</td>
                        <td class="col-meters">{{ $totalMeters }}m</td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        @endif
    </div>
    @endforeach
@endif

{{-- Opmerkingen / aandachtspunten --}}
<div class="checkboxes">
    <h2>Opmerkingen installateur</h2>
    <div style="border: 1pt solid #c8cdd8; border-radius: 2pt; min-height: 30mm; padding: 3mm 4mm;">
        &nbsp;
    </div>
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
