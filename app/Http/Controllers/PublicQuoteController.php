<?php

namespace App\Http\Controllers;

use App\Models\Quote;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicQuoteController extends Controller
{
    public function __invoke(Request $request, string $token): View|\Illuminate\Contracts\View\View
    {
        $quote = Quote::where('sign_token', $token)
            ->with(['customer', 'user', 'items.product'])
            ->first();

        if (!$quote) {
            abort(404, 'Deze offerte-link is niet geldig.');
        }

        if ($quote->sign_token_expires_at && $quote->sign_token_expires_at->isPast()) {
            return view('public.quote-expired', compact('quote'));
        }

        $onetimeItems = $quote->items->filter(
            fn ($item) => $item->product && $item->product->unit !== 'jaar' && !$item->product->is_price_on_quote
        );
        $yearlyItems = $quote->items->filter(
            fn ($item) => $item->product && $item->product->unit === 'jaar' && !$item->product->is_price_on_quote
        );
        $onQuoteItems = $quote->items->filter(
            fn ($item) => $item->product && $item->product->is_price_on_quote
        );

        return view('public.quote', compact('quote', 'onetimeItems', 'yearlyItems', 'onQuoteItems'));
    }
}
