<!DOCTYPE html>
<html lang="nl">
<head>
<meta charset="UTF-8">
<style>
* { margin: 0; padding: 0; box-sizing: border-box; }
body {
    font-family: DejaVu Sans, Arial, sans-serif;
    font-size: 7.5pt;
    color: #888888;
}
.footer-inner {
    width: 100%;
    border-top: 1pt solid #cccccc;
    padding-top: 3mm;
}
table { width: 100%; border-collapse: collapse; }
</style>
</head>
<body>
<div class="footer-inner">
    <table>
        <tr>
            <td>{{ $settings->get('company_name', 'Proud Innovations B.V.') }} &nbsp;|&nbsp; {{ $settings->get('company_address', 'Zoetermeer') }}</td>
            <td style="text-align: right;">Pagina <span class="page"></span> van <span class="topage"></span></td>
        </tr>
    </table>
</div>
<script>
    // wkhtmltopdf vult deze spans automatisch in
    var vars = {};
    var query = window.location.search.substring(1).split('&');
    for (var i = 0; i < query.length; i++) {
        var pair = query[i].split('=');
        vars[pair[0]] = decodeURIComponent(pair[1]);
    }
    document.querySelector('.page').textContent = vars.page || '';
    document.querySelector('.topage').textContent = vars.topage || '';
</script>
</body>
</html>
