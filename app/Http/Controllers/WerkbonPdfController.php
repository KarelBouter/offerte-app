<?php

namespace App\Http\Controllers;

use App\Models\Quote;
use App\Services\WerkbonPdfService;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class WerkbonPdfController extends Controller
{
    public function __invoke(Quote $quote, WerkbonPdfService $service): StreamedResponse
    {
        abort_unless($quote->status === 'ondertekend', 403, 'De werkbon-PDF is pas beschikbaar zodra de offerte is ondertekend.');

        $filename = 'werkbonnen/' . $quote->quote_number . '-v' . $quote->revision . '-werkbon.pdf';

        $service->generate($quote);

        $download = 'Werkbon_' . $quote->quote_number . '-v' . $quote->revision . '_ProudInnovations.pdf';

        return Storage::disk('local')->download($filename, $download, [
            'Content-Type' => 'application/pdf',
        ]);
    }
}
