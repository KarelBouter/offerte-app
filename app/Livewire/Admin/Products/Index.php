<?php

namespace App\Livewire\Admin\Products;

use App\Models\Product;
use Livewire\Component;

class Index extends Component
{
    public string $search = '';
    public string $categoryFilter = '';

    public ?int $confirmingId       = null;
    public string $confirmingName   = '';
    public bool $confirmingIsActive = false;

    public function prepareConfirmToggle(int $id, string $name, bool $isActive): void
    {
        $this->confirmingId       = $id;
        $this->confirmingName     = $name;
        $this->confirmingIsActive = $isActive;
        $this->dispatch('open-modal', 'confirm-product');
    }

    public function toggleActive(int $productId): void
    {
        $product = Product::findOrFail($productId);
        $product->update(['is_active' => ! $product->is_active]);
        $this->dispatch('close-modal', 'confirm-product');
    }

    public function render()
    {
        $products = Product::query()
            ->when($this->search, fn ($q) => $q->where(function ($q) {
                $q->where('name', 'like', '%'.$this->search.'%')
                    ->orWhere('category', 'like', '%'.$this->search.'%');
            }))
            ->when($this->categoryFilter, fn ($q) => $q->where('category', $this->categoryFilter))
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('livewire.admin.products.index', ['products' => $products])
            ->layout('layouts.app-admin', ['title' => 'Producten']);
    }
}
