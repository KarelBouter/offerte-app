<?php

namespace App\Livewire\Verkoper\Quotes;

use App\Models\Quote;
use Illuminate\Http\Request;
use Livewire\Component;

class Index extends Component
{
    public string $search       = '';
    public string $statusFilter = '';
    public string $urlFilter    = '';

    public const STATUS_LABELS = [
        'concept'     => 'Concept',
        'verzonden'   => 'Verzonden',
        'ondertekend' => 'Ondertekend',
        'verlopen'    => 'Verlopen',
        'geannuleerd' => 'Geannuleerd',
    ];

    public const URL_FILTER_LABELS = [
        'bijna_verlopen'        => 'Bijna verlopen',
        'ondertekend_deze_maand' => 'Ondertekend deze maand',
    ];

    public function mount(Request $request): void
    {
        if ($request->filled('status')) {
            $this->statusFilter = $request->get('status');
        }
        if ($request->filled('filter')) {
            $this->urlFilter = $request->get('filter');
        }
    }

    public function clearFilter(): void
    {
        $this->statusFilter = '';
        $this->urlFilter    = '';
    }

    public function render()
    {
        $quotes = Quote::with(['customer', 'user'])
            ->when($this->search, fn ($q) => $q->where(function ($q) {
                $q->where('quote_number', 'like', '%'.$this->search.'%')
                    ->orWhereHas('customer', fn ($q) => $q->where('company_name', 'like', '%'.$this->search.'%'));
            }))
            ->when($this->statusFilter, fn ($q) => $q->where('status', $this->statusFilter))
            ->when($this->urlFilter === 'bijna_verlopen', fn ($q) => $q
                ->whereIn('status', ['concept', 'verzonden'])
                ->whereNotNull('valid_until')
                ->whereDate('valid_until', '>=', today())
                ->whereDate('valid_until', '<=', today()->addDays(7))
            )
            ->when($this->urlFilter === 'ondertekend_deze_maand', fn ($q) => $q
                ->where('status', 'ondertekend')
                ->whereYear('updated_at', now()->year)
                ->whereMonth('updated_at', now()->month)
            )
            ->latest()
            ->get();

        return view('livewire.verkoper.quotes.index', [
            'quotes'       => $quotes,
            'statusLabels' => self::STATUS_LABELS,
        ])->layout('layouts.app-verkoper', ['title' => 'Offertes']);
    }
}
