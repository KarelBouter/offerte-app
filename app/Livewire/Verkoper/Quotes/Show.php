<?php

namespace App\Livewire\Verkoper\Quotes;

use App\Models\Quote;
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

    public function duplicate(): void
    {
        $new = $this->quote->replicate(['quote_number', 'created_at', 'updated_at']);
        $new->status = 'concept';
        $new->user_id = auth()->id();
        $new->save();

        foreach ($this->quote->items as $item) {
            $newItem = $item->replicate(['quote_id']);
            $newItem->quote_id = $new->id;
            $newItem->save();
        }

        session()->flash('success', 'Offerte gedupliceerd als '.$new->quote_number.'.');
        $this->redirect(route('verkoper.quotes.show', $new));
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
