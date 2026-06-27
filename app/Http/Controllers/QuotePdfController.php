<?php

namespace App\Http\Controllers;

use App\Models\Quote;
use App\Services\QuotePdfService;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class QuotePdfController extends Controller
{
    public function __invoke(Quote $quote, QuotePdfService $service): StreamedResponse
    {
        if (!$quote->pdf_path || !Storage::disk('local')->exists($quote->pdf_path)) {
            $service->generate($quote);
            $quote->refresh();
        }

        $filename = 'Offerte_'.$quote->quote_number.'-v'.$quote->revision.'_ProudInnovations.pdf';

        return Storage::disk('local')->download($quote->pdf_path, $filename, [
            'Content-Type' => 'application/pdf',
        ]);
    }
}
