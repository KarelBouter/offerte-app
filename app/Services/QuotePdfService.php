<?php

namespace App\Services;

use App\Models\Quote;
use App\Models\Setting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class QuotePdfService
{
    public function generate(Quote $quote): string
    {
        $quote->load(['customer', 'items.product', 'user']);

        $items = $quote->items->filter(fn ($i) => $i->product !== null);

        // ── Determine selected hardware option ──────────────────────────────
        $hwOptionA = $items->first(fn ($i) => str_starts_with($i->product->name, 'Optie A'));
        $hwOptionB = $items->first(fn ($i) => str_starts_with($i->product->name, 'Optie B'));
        $chosenHw  = $hwOptionA ?? $hwOptionB;

        // UPS
        $upsItem = $items->first(fn ($i) => $i->product->name === 'UPS');

        // ── Service contract ────────────────────────────────────────────────
        $svcItem = $items->first(fn ($i) => $i->product->category === 'Service');

        // ── Installation items ──────────────────────────────────────────────
        $installItems = $items->filter(
            fn ($i) => $i->product->category === 'Installatie' && !$i->product->is_price_on_quote
        );

        // ── Add-on items (Netwerk, Beveiliging, and on-quote installation) ──
        $addonItems = $items->filter(function ($i) {
            return in_array($i->product->category, ['Netwerk', 'Beveiliging'])
                || ($i->product->category === 'Installatie' && $i->product->is_price_on_quote);
        });

        // ── Company settings ────────────────────────────────────────────────
        $settings = [
            'company_name'           => Setting::get('company_name', 'Proud Innovations B.V.'),
            'company_address'        => Setting::get('company_address', 'Zoetermeer'),
            'company_kvk'            => Setting::get('company_kvk', ''),
            'company_representative' => Setting::get('company_representative', ''),
            'vat_percentage'         => (float) Setting::get('vat_percentage', '21'),
        ];

        $pdf = Pdf::loadView('pdf.quote', [
            'quote'        => $quote,
            'settings'     => $settings,
            'chosenHw'     => $chosenHw,
            'hwOptionA'    => $hwOptionA,
            'hwOptionB'    => $hwOptionB,
            'upsItem'      => $upsItem,
            'svcItem'      => $svcItem,
            'installItems' => $installItems,
            'addonItems'   => $addonItems,
            'allItems'     => $items,
        ])->setPaper('a4', 'portrait');

        $filename  = 'quotes/'.$quote->quote_number.'.pdf';
        $pdfOutput = $pdf->output();

        Storage::disk('local')->put($filename, $pdfOutput);

        $quote->update(['pdf_path' => $filename]);

        return $filename;
    }
}
