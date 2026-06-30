<?php

namespace App\Services;

use App\Models\Quote;
use App\Models\Setting;
use App\Support\PdfDefaults;
use Illuminate\Support\Facades\Storage;
use Knp\Snappy\Pdf;

class WerkbonPdfService
{
    public function generate(Quote $quote): string
    {
        $quote->load(['customer', 'items.product', 'user']);

        $allItems = $quote->items
            ->filter(fn($i) => $i->product !== null)
            ->sortBy('sort_order')
            ->filter(function ($item) {
                // Expliciete override per item heeft prioriteit
                if ($item->werkbon_verborgen !== null) {
                    return !$item->werkbon_verborgen;
                }
                // Fallback op product-instelling
                $zichtbaarheid = $item->product->werkbon_zichtbaarheid ?? 'automatisch';
                if ($zichtbaarheid === 'verbergen') {
                    return !empty($item->installatie_notitie) || !empty($item->werkbon_aantekening);
                }
                return true;
            })
            ->values();

        $defaults = collect([
            'company_name'    => 'Proud Innovations B.V.',
            'company_address' => 'Zoetermeer',
            'company_email'   => '',
            'company_phone'   => '',
            'pdf_primary_color' => PdfDefaults::PRIMARY_COLOR,
        ]);

        $settings = $defaults->merge(Setting::all()->pluck('value', 'key'));

        $logoSrc = null;
        foreach (['company_logo', 'logo_path', 'pdf_logo'] as $logoKey) {
            $logoPath = $settings->get($logoKey);
            if (!$logoPath) continue;
            if (Storage::disk('public')->exists($logoPath)) {
                $logoSrc = Storage::disk('public')->path($logoPath);
                break;
            }
            if (file_exists($logoPath)) {
                $logoSrc = $logoPath;
                break;
            }
        }

        $html = view('pdf.werkbon', [
            'quote'    => $quote,
            'settings' => $settings,
            'logoSrc'  => $logoSrc,
            'allItems' => $allItems,
        ])->render();

        $headerHtml = view('pdf.werkbon-header', [
            'quote'    => $quote,
            'settings' => $settings,
            'logoSrc'  => $logoSrc,
        ])->render();

        $footerHtml = view('pdf.footer', [
            'settings' => $settings,
        ])->render();

        $tmpDir = storage_path('app/tmp');
        if (!is_dir($tmpDir)) {
            mkdir($tmpDir, 0755, true);
        }

        $headerFile = $tmpDir . '/werkbon_header_' . $quote->id . '.html';
        $footerFile = $tmpDir . '/werkbon_footer_' . $quote->id . '.html';

        file_put_contents($headerFile, $headerHtml);
        file_put_contents($footerFile, $footerHtml);

        $snappy = new Pdf(config('snappy.pdf.binary'));
        $snappy->setOptions([
            'page-size'                => 'A4',
            'margin-top'               => '35mm',
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

        @unlink($headerFile);
        @unlink($footerFile);

        $filename = 'werkbonnen/' . $quote->quote_number . '-v' . $quote->revision . '-werkbon.pdf';
        Storage::disk('local')->put($filename, $pdfOutput);

        return $filename;
    }
}
