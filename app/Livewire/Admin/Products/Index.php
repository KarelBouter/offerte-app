<?php

namespace App\Livewire\Admin\Products;

use App\Models\Product;
use Livewire\Component;

class Index extends Component
{
    public string $search = '';
    public string $categoryFilter = '';

    public function toggleActive(int $productId): void
    {
        $product = Product::findOrFail($productId);
        $product->update(['is_active' => ! $product->is_active]);
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
