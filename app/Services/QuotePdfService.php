<?php

namespace App\Services;

use App\Models\Quote;
use App\Models\Setting;
use App\Support\PdfDefaults;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class QuotePdfService
{
    public function generate(Quote $quote): string
    {
        $quote->load(['customer', 'items.product', 'user']);

        $items = $quote->items->filter(fn ($i) => $i->product !== null);

        // ── Item categorisering ──────────────────────────────────────────────
        $hwOptionA    = $items->first(fn ($i) => str_starts_with($i->product->name, 'Optie A'));
        $hwOptionB    = $items->first(fn ($i) => str_starts_with($i->product->name, 'Optie B'));
        $chosenHw     = $hwOptionA ?? $hwOptionB;
        $upsItem      = $items->first(fn ($i) => $i->product->name === 'UPS');
        $svcItem      = $items->first(fn ($i) => $i->product->category === 'Service');
        $installItems = $items->filter(
            fn ($i) => $i->product->category === 'Installatie' && !$i->product->is_price_on_quote
        );
        $addonItems   = $items->filter(function ($i) {
            return in_array($i->product->category, ['Netwerk', 'Beveiliging'])
                || ($i->product->category === 'Installatie' && $i->product->is_price_on_quote);
        });

        // ── Settings: defaults + DB in één Collection ────────────────────────
        $defaults = collect([
            'company_name'           => 'Proud Innovations B.V.',
            'company_address'        => 'Zoetermeer',
            'company_kvk'            => '',
            'company_representative' => '',
            'company_email'          => '',
            'company_phone'          => '',
            'vat_percentage'         => '21',
            'default_quote_note'     => '',
            'pdf_primary_color'      => PdfDefaults::PRIMARY_COLOR,
            'pdf_font_family'        => PdfDefaults::FONT_FAMILY,
            'pdf_font_size_body'     => PdfDefaults::FONT_SIZE_BODY,
            'pdf_font_size_heading'  => PdfDefaults::FONT_SIZE_HEADING,
            'pdf_tekst_artikel_2'             => PdfDefaults::ARTIKEL_2,
            'pdf_tekst_artikel_2_afbakening'  => PdfDefaults::ARTIKEL_2_AFBAKENING,
            'pdf_tekst_artikel_3'             => PdfDefaults::ARTIKEL_3,
            'pdf_tekst_artikel_3_2'           => PdfDefaults::ARTIKEL_3_2,
            'pdf_tekst_artikel_5'             => PdfDefaults::ARTIKEL_5,
            'pdf_tekst_artikel_6_afbakening'  => PdfDefaults::ARTIKEL_6_AFBAKENING,
            'pdf_tekst_artikel_6_2'           => PdfDefaults::ARTIKEL_6_2,
            'pdf_tekst_afbakening_service'    => PdfDefaults::AFBAKENING_SERVICE,
            'pdf_tekst_artikel_7'             => PdfDefaults::ARTIKEL_7,
            'pdf_tekst_artikel_8'             => PdfDefaults::ARTIKEL_8,
            'pdf_tekst_artikel_8_2'           => PdfDefaults::ARTIKEL_8_2,
            'pdf_tekst_artikel_9_1'           => PdfDefaults::ARTIKEL_9_1,
            'pdf_tekst_artikel_9_2'           => PdfDefaults::ARTIKEL_9_2,
            'pdf_tekst_artikel_9_3'           => PdfDefaults::ARTIKEL_9_3,
            'pdf_tekst_artikel_9_4'           => PdfDefaults::ARTIKEL_9_4,
            'pdf_tekst_artikel_9_5'           => PdfDefaults::ARTIKEL_9_5,
            'pdf_tekst_artikel_9_6'           => PdfDefaults::ARTIKEL_9_6,
            'pdf_tekst_artikel_9_7'           => PdfDefaults::ARTIKEL_9_7,
            'pdf_tekst_artikel_10_footer'     => PdfDefaults::ARTIKEL_10_FOOTER,
        ]);

        // DB-waarden overschrijven de defaults (één query)
        $settings = $defaults->merge(Setting::all()->pluck('value', 'key'));

        // ── Logo ─────────────────────────────────────────────────────────────
        // DomPDF rendert geen afbeeldingen via data: URI in position:fixed elementen.
        // Oplossing: file:// absoluut pad meegeven zodat DomPDF het bestand direct leest.
        $logoSrc  = null;
        $logoPath = $settings->get('company_logo') ?? $settings->get('logo_path');
        if ($logoPath && Storage::disk('public')->exists($logoPath)) {
            $logoSrc = 'file://' . Storage::disk('public')->path($logoPath);
        }
        // Backwards-compat: oude code gebruikte $logoBase64/$logoMime in de view
        $logoBase64 = null;
        $logoMime   = 'image/png';

        // ── Vervang {{vat_pct}} in artikel 10 footer ─────────────────────────
        $vatPct = (float) $settings->get('vat_percentage', '21');
        $settings->put('pdf_tekst_artikel_10_footer', str_replace(
            '{{vat_pct}}',
            number_format($vatPct, 0),
            $settings->get('pdf_tekst_artikel_10_footer')
        ));

        // ── Genereer PDF ─────────────────────────────────────────────────────
        $pdf = Pdf::loadView('pdf.quote', [
            'quote'        => $quote,
            'settings'     => $settings,
            'logoSrc'      => $logoSrc,
            'logoBase64'   => $logoBase64,
            'logoMime'     => $logoMime,
            'chosenHw'     => $chosenHw,
            'hwOptionA'    => $hwOptionA,
            'hwOptionB'    => $hwOptionB,
            'upsItem'      => $upsItem,
            'svcItem'      => $svcItem,
            'installItems' => $installItems,
            'addonItems'   => $addonItems,
            'allItems'     => $items,
        ])
        ->setPaper('a4', 'portrait')
        ->setOptions([
            'isRemoteEnabled'         => true,
            'isHtml5ParserEnabled'    => true,
            'isFontSubsettingEnabled' => true,
            'defaultFont'             => 'DejaVu Sans',
            'enable_html5_parser'     => true,
            'dpi'                     => 150,
            'defaultMediaType'        => 'print',
        ], true);

        $filename = 'quotes/' . $quote->quote_number . '.pdf';
        Storage::disk('local')->put($filename, $pdf->output());
        $quote->update(['pdf_path' => $filename]);

        return $filename;
    }
}
