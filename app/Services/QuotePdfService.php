<?php

namespace App\Services;

use App\Models\Quote;
use App\Models\Setting;
use App\Support\PdfDefaults;
use Illuminate\Support\Facades\Storage;
use Knp\Snappy\Pdf;

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

        // ── Settings: defaults + DB ──────────────────────────────────────────
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
            'pdf_tekst_artikel_10_footer'      => PdfDefaults::ARTIKEL_10_FOOTER,
            'pdf_tekst_artikel_10_footer_kaal' => PdfDefaults::ARTIKEL_10_FOOTER_KAAL,
        ]);

        $settings = $defaults->merge(Setting::all()->pluck('value', 'key'));

        // ── Logo ─────────────────────────────────────────────────────────────
        // wkhtmltopdf kan gewone file:// paden aan — geen base64 nodig.
        $logoSrc = null;
        foreach (['company_logo', 'logo_path', 'pdf_logo'] as $logoKey) {
            $logoPath = $settings->get($logoKey);
            if (!$logoPath) continue;
            if (Storage::disk('public')->exists($logoPath)) {
                $logoSrc = Storage::disk('public')->path($logoPath);
                break;
            }
            if (Storage::disk('local')->exists($logoPath)) {
                $logoSrc = Storage::disk('local')->path($logoPath);
                break;
            }
            if (file_exists($logoPath)) {
                $logoSrc = $logoPath;
                break;
            }
        }

        // ── Handtekening klant ────────────────────────────────────────────────
        $signatureSrc = null;
        if ($quote->signature_path && Storage::disk('local')->exists($quote->signature_path)) {
            $signatureSrc = Storage::disk('local')->path($quote->signature_path);
        }

        // ── Handtekening Proud Innovations (alleen bij mede-ondertekend) ──────
        $companySigSrc = null;
        if ($quote->cosigned_at) {
            $companySigPath = $settings->get('company_signature_path');
            if ($companySigPath && Storage::disk('public')->exists($companySigPath)) {
                $companySigSrc = Storage::disk('public')->path($companySigPath);
            }
        }

        // ── Vervang {{vat_pct}} in artikel 10 footers ────────────────────────
        $vatPct = (float) $settings->get('vat_percentage', '21');
        foreach (['pdf_tekst_artikel_10_footer', 'pdf_tekst_artikel_10_footer_kaal'] as $key) {
            $settings->put($key, str_replace('{{vat_pct}}', number_format($vatPct, 0), $settings->get($key)));
        }

        // ── Render HTML ───────────────────────────────────────────────────────
        $html = view('pdf.quote', [
            'quote'         => $quote,
            'settings'      => $settings,
            'logoSrc'       => $logoSrc,
            'signatureSrc'  => $signatureSrc,
            'companySigSrc' => $companySigSrc,
            'chosenHw'      => $chosenHw,
            'hwOptionA'     => $hwOptionA,
            'hwOptionB'     => $hwOptionB,
            'upsItem'       => $upsItem,
            'svcItem'       => $svcItem,
            'installItems'  => $installItems,
            'addonItems'    => $addonItems,
            'allItems'      => $items,
        ])->render();

        // ── Header en footer HTML ─────────────────────────────────────────────
        $headerHtml = view('pdf.header', [
            'quote'    => $quote,
            'settings' => $settings,
            'logoSrc'  => $logoSrc,
        ])->render();

        $footerHtml = view('pdf.footer', [
            'settings' => $settings,
        ])->render();

        // Schrijf tijdelijke HTML bestanden (wkhtmltopdf leest ze via file://)
        $tmpDir = storage_path('app/tmp');
        if (!is_dir($tmpDir)) {
            mkdir($tmpDir, 0755, true);
        }

        $headerFile = $tmpDir . '/header_' . $quote->id . '.html';
        $footerFile = $tmpDir . '/footer_' . $quote->id . '.html';

        file_put_contents($headerFile, $headerHtml);
        file_put_contents($footerFile, $footerHtml);

        // ── Genereer PDF via wkhtmltopdf ──────────────────────────────────────
        $snappy = new Pdf(config('snappy.pdf.binary'));
        $snappy->setOptions([
            'page-size'                => 'A4',
            'margin-top'               => '48mm',
            'margin-bottom'            => '22mm',
            'margin-left'              => '20mm',
            'margin-right'             => '20mm',
            'encoding'                 => 'UTF-8',
            'enable-local-file-access' => true,
            'print-media-type'         => true,
            'header-html'              => $headerFile,
            'footer-html'              => $footerFile,
            'header-spacing'           => 3,
            'footer-spacing'           => 3,
        ]);

        $pdfOutput = $snappy->getOutputFromHtml($html);

        // Opruimen tijdelijke bestanden
        @unlink($headerFile);
        @unlink($footerFile);

        // ── Opslaan ───────────────────────────────────────────────────────────
        $filename = 'quotes/' . $quote->quote_number . '-v' . $quote->revision . '.pdf';
        Storage::disk('local')->put($filename, $pdfOutput);
        $quote->update(['pdf_path' => $filename]);

        return $filename;
    }
}
