<?php

namespace App\Livewire\Verkoper\Quotes;

use App\Models\Quote;
use Livewire\Component;

class Index extends Component
{
    public string $search = '';
    public string $statusFilter = '';

    public const STATUS_LABELS = [
        'concept'     => 'Concept',
        'verzonden'   => 'Verzonden',
        'ondertekend' => 'Ondertekend',
        'verlopen'    => 'Verlopen',
        'geannuleerd' => 'Geannuleerd',
    ];

    public function render()
    {
        $quotes = Quote::with(['customer', 'user'])
            ->when($this->search, fn ($q) => $q->where(function ($q) {
                $q->where('quote_number', 'like', '%'.$this->search.'%')
                    ->orWhereHas('customer', fn ($q) => $q->where('company_name', 'like', '%'.$this->search.'%'));
            }))
            ->when($this->statusFilter, fn ($q) => $q->where('status', $this->statusFilter))
            ->latest()
            ->get();

        return view('livewire.verkoper.quotes.index', [
            'quotes' => $quotes,
            'statusLabels' => self::STATUS_LABELS,
        ])->layout('layouts.app-verkoper', ['title' => 'Offertes']);
    }
}
