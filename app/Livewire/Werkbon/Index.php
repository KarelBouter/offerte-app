<?php

namespace App\Livewire\Werkbon;

use App\Models\Quote;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $search        = '';
    public string $afgerondFilter = '';   // '' = alle, '1' = afgerond, '0' = niet afgerond

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingAfgerondFilter(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $layout = auth()->user()->role === 'admin' ? 'layouts.app-admin' : 'layouts.app-verkoper';

        $quotes = Quote::with(['customer', 'werkbonBewerker'])
            ->where('status', 'ondertekend')
            ->when($this->search, function ($q) {
                $q->where(function ($q2) {
                    $q2->where('quote_number', 'like', '%' . $this->search . '%')
                       ->orWhereHas('customer', fn($c) => $c->where('company_name', 'like', '%' . $this->search . '%'));
                });
            })
            ->when($this->afgerondFilter !== '', fn($q) => $q->where('werkbon_afgerond', (bool) $this->afgerondFilter))
            ->orderBy('werkbon_afgerond')          // niet-afgerond (0) eerst
            ->orderByDesc('updated_at')
            ->paginate(20);

        return view('livewire.werkbon.index', compact('quotes'))
            ->layout($layout, ['title' => 'Werkbonnen']);
    }
}
