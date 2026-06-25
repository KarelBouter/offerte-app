<?php

namespace App\Livewire\Admin\Dashboard;

use App\Models\Product;
use App\Models\Quote;
use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        $totalProducts = Product::where('is_active', true)->count();
        $totalQuotes   = Quote::count();
        $quotesMonth   = Quote::whereYear('created_at', now()->year)
                              ->whereMonth('created_at', now()->month)
                              ->count();
        $revenueMonth  = Quote::whereYear('created_at', now()->year)
                              ->whereMonth('created_at', now()->month)
                              ->whereIn('status', ['concept', 'verzonden'])
                              ->sum('total_onetime_excl_vat');

        $recentQuotes     = Quote::with(['customer', 'user'])->latest()->limit(10)->get();
        $productsOnQuote  = Product::where('is_price_on_quote', true)->where('is_active', true)->get();

        return view('livewire.admin.dashboard.index', compact(
            'totalProducts', 'totalQuotes', 'quotesMonth', 'revenueMonth',
            'recentQuotes', 'productsOnQuote'
        ))->layout('layouts.app-admin', ['title' => 'Dashboard']);
    }
}
