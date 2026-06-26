<?php

namespace App\Livewire\Verkoper\Quotes;

use App\Mail\QuoteClientMail;
use App\Models\Quote;
use App\Services\ActivityLogService;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class Show extends Component
{
    public Quote $quote;

    public const STATUS_LABELS = [
        'concept'     => 'Concept',
        'verzonden'   => 'Verzonden',
        'ondertekend' => 'Ondertekend',
        'verlopen'    => 'Verlopen',
        'geannuleerd' => 'Geannuleerd',
    ];

    public const STATUS_COLORS = [
        'concept'     => 'bg-gray-100 text-gray-600',
        'verzonden'   => 'bg-blue-100 text-blue-700',
        'ondertekend' => 'bg-green-100 text-green-700',
        'verlopen'    => 'bg-orange-100 text-orange-700',
        'geannuleerd' => 'bg-red-100 text-red-700',
    ];

    public function mount(Quote $quote): void
    {
        $this->quote = $quote->load(['customer', 'user', 'items.product']);
    }

    public function updateStatus(string $status): void
    {
        if (!array_key_exists($status, self::STATUS_LABELS)) {
            return;
        }
        $this->quote->update(['status' => $status]);
        $this->quote->refresh();
        session()->flash('success', 'Status bijgewerkt naar "'.self::STATUS_LABELS[$status].'".');
    }

    public function sendToClient(): void
    {
        $token   = $this->quote->generateSignToken();
        $signUrl = route('quote.public', $token);

        $this->quote->update(['status' => 'verzonden']);
        $this->quote->refresh();

        Mail::to($this->quote->customer->contact_email)
            ->send(new QuoteClientMail($this->quote, $signUrl));

        app(ActivityLogService::class)->log(
            'quote.sent',
            $this->quote,
            'Offerte '.$this->quote->quote_number.' verstuurd naar '.$this->quote->customer->contact_email
        );

        session()->flash('success', 'Offerte verstuurd naar '.$this->quote->customer->contact_email.'.');
    }

    public function duplicate(): void
    {
        $new = $this->quote->replicate();
        $new->quote_number  = null;
        $new->pdf_path      = null;
        $new->status        = 'concept';
        $new->user_id       = auth()->id();
        $new->valid_until   = now()->addDays(30)->toDateString();
        $new->save();

        foreach ($this->quote->items as $item) {
            $newItem = $item->replicate();
            $newItem->quote_id = $new->id;
            $newItem->save();
        }

        session()->flash('success', 'Offerte gekopieerd. Je bewerkt nu een nieuwe conceptofferte.');
        $this->redirect(route('verkoper.offertes.edit', $new));
    }

    public function render()
    {
        $onetimeItems = $this->quote->items->filter(
            fn ($item) => $item->product && $item->product->unit !== 'jaar' && !$item->product->is_price_on_quote
        );
        $yearlyItems = $this->quote->items->filter(
            fn ($item) => $item->product && $item->product->unit === 'jaar' && !$item->product->is_price_on_quote
        );
        $onQuoteItems = $this->quote->items->filter(
            fn ($item) => $item->product && $item->product->is_price_on_quote
        );

        return view('livewire.verkoper.quotes.show', [
            'onetimeItems'   => $onetimeItems,
            'yearlyItems'    => $yearlyItems,
            'onQuoteItems'   => $onQuoteItems,
            'statusLabels'   => self::STATUS_LABELS,
            'statusColors'   => self::STATUS_COLORS,
        ])->layout('layouts.app-verkoper', ['title' => 'Offerte '.$this->quote->quote_number]);
    }
}
