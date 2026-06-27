<?php

namespace App\Livewire\Verkoper\Customers;

use App\Models\Customer;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $search = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $customers = Customer::query()
            ->when($this->search, fn ($q) => $q
                ->where('company_name', 'like', '%'.$this->search.'%')
                ->orWhere('contact_name', 'like', '%'.$this->search.'%')
                ->orWhere('kvk_number', 'like', '%'.$this->search.'%')
            )
            ->withCount('quotes')
            ->orderBy('company_name')
            ->paginate(20);

        $layout = auth()->user()->role === 'admin' ? 'layouts.app-admin' : 'layouts.app-verkoper';

        return view('livewire.verkoper.customers.index', [
            'customers' => $customers,
        ])->layout($layout, ['title' => 'Klanten']);
    }
}
