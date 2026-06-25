<?php

namespace App\Livewire\Verkoper;

use App\Models\Quote;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $userId = auth()->id();

        $totalQuotes  = Quote::count();
        $myQuotes     = Quote::where('user_id', $userId)->count();
        $conceptCount = Quote::where('status', 'concept')->count();
        $signedCount  = Quote::where('status', 'ondertekend')->count();

        $recentQuotes = Quote::with(['customer', 'user'])->latest()->limit(10)->get();

        return view('livewire.verkoper.dashboard.index', compact(
            'totalQuotes', 'myQuotes', 'conceptCount', 'signedCount', 'recentQuotes'
        ))->layout('layouts.app-verkoper', ['title' => 'Dashboard']);
    }
}
