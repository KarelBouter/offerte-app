<?php

namespace App\Http\Controllers;

use App\Models\Quote;
use App\Services\QuotePdfService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PublicQuoteSignController extends Controller
{
    public function __invoke(Request $request, string $token)
    {
        $quote = Quote::where('sign_token', $token)
            ->with(['customer', 'items.product'])
            ->firstOrFail();

        if ($quote->sign_token_expires_at?->isPast()) {
            return redirect()->route('quote.public', $token)
                ->with('error', 'Deze link is verlopen.');
        }

        if ($quote->status === 'ondertekend') {
            return redirect()->route('quote.public', $token)
                ->with('error', 'Deze offerte is al ondertekend.');
        }

        $requireSignature = $request->input('require_signature', '1') === '1';

        if ($requireSignature) {
            $request->validate([
                'signature'   => 'required|string',
                'signer_name' => 'required|string|max:255',
            ], [
                'signature.required'   => 'Handtekening is verplicht.',
                'signer_name.required' => 'Naam is verplicht.',
            ]);

            $dataUrl = $request->input('signature');
            if (!str_starts_with($dataUrl, 'data:image/png;base64,')) {
                return back()->with('error', 'Ongeldige handtekening.');
            }

            $base64    = substr($dataUrl, strlen('data:image/png;base64,'));
            $imageData = base64_decode($base64);

            if ($imageData === false || strlen($imageData) < 100) {
                return back()->with('error', 'Ongeldige handtekening.');
            }

            if (strlen($imageData) > 1024 * 1024) {
                return back()->with('error', 'Handtekening is te groot (max 1 MB).');
            }

            $signaturePath = 'signatures/' . $quote->id . '_' . time() . '.png';
            Storage::disk('local')->put($signaturePath, $imageData);

            $quote->update([
                'status'         => 'ondertekend',
                'signed_at'      => now(),
                'signed_by_name' => $request->input('signer_name'),
                'signature_path' => $signaturePath,
                'signed_ip'      => $request->ip(),
            ]);
        } else {
            $request->validate([
                'signer_name' => 'required|string|max:255',
            ], [
                'signer_name.required' => 'Naam is verplicht.',
            ]);

            $quote->update([
                'status'         => 'ondertekend',
                'signed_at'      => now(),
                'signed_by_name' => $request->input('signer_name'),
                'signature_path' => null,
                'signed_ip'      => $request->ip(),
            ]);
        }

        app(QuotePdfService::class)->generate($quote->fresh(['customer', 'items.product', 'user']));

        return redirect()->route('quote.signed', $token);
    }
}
