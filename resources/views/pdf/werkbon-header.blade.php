<!DOCTYPE html>
<html lang="nl">
<head>
<meta charset="UTF-8">
<style>
* { margin: 0; padding: 0; box-sizing: border-box; }
body {
    font-family: DejaVu Sans, Arial, sans-serif;
    font-size: 8pt;
    color: #1a1a1a;
}
.header-inner {
    width: 100%;
    border-bottom: 2pt solid #1B3A6B;
    padding-bottom: 3mm;
    padding-top: 3mm;
}
table { width: 100%; border-collapse: collapse; }
.logo-cel {
    width: 50%;
    vertical-align: middle;
    font-size: 12pt;
    font-weight: bold;
    color: #1B3A6B;
}
.info-cel {
    width: 50%;
    text-align: right;
    vertical-align: middle;
    font-size: 7.5pt;
    color: #555555;
    line-height: 1.7;
}
</style>
</head>
<body>
<div class="header-inner">
    <table>
        <tr>
            <td class="logo-cel">
                @if(!empty($logoSrc))
                    <img src="file://{{ $logoSrc }}" style="max-height: 12mm; max-width: 50mm;">
                @else
                    {{ $settings->get('company_name', 'Proud Innovations B.V.') }}
                @endif
            </td>
            <td class="info-cel">
                <strong>Werkbon</strong> — Kassa Continuïteitsdienst<br>
                Offertenummer: <b>{{ $quote->quote_number }}</b> &nbsp;<span style="color:#888">v{{ $quote->revision }}</span><br>
                Datum: {{ \Carbon\Carbon::parse($quote->created_at)->format('d-m-Y') }}
            </td>
        </tr>
    </table>
</div>
</body>
</html>
