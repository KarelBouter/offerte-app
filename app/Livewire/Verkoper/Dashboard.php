<?php

namespace App\Livewire\Verkoper;

use App\Models\Quote;
use App\Models\Task;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        // ── Actie vereist ───────────────────────────────────────────────────
        // 1. Verzonden > 7 dagen geleden, nog geen reactie
        $sentNoResponse = Quote::where('status', 'verzonden')
            ->where('updated_at', '<', now()->subDays(7))
            ->with(['customer', 'user'])
            ->orderBy('updated_at')
            ->get()
            ->map(fn ($q) => [
                'quote'    => $q,
                'priority' => 1,
                'reason'   => 'Wacht op ondertekening — '.now()->diffInDays($q->updated_at).' dagen geleden verstuurd',
            ]);

        // 2. Concept, valid_until binnen 7 dagen
        $conceptExpiring = Quote::where('status', 'concept')
            ->whereNotNull('valid_until')
            ->whereDate('valid_until', '>=', today())
            ->whereDate('valid_until', '<=', today()->addDays(7))
            ->with(['customer', 'user'])
            ->orderBy('valid_until')
            ->get()
            ->map(fn ($q) => [
                'quote'    => $q,
                'priority' => 2,
                'reason'   => 'Verloopt over '.today()->diffInDays($q->valid_until).' '.(today()->diffInDays($q->valid_until) === 1 ? 'dag' : 'dagen'),
            ]);

        // 3. Concept ouder dan 14 dagen, nooit verstuurd
        $staleDrafts = Quote::where('status', 'concept')
            ->where('created_at', '<', now()->subDays(14))
            ->with(['customer', 'user'])
            ->orderBy('created_at')
            ->get()
            ->map(fn ($q) => [
                'quote'    => $q,
                'priority' => 3,
                'reason'   => 'Concept — '.now()->diffInDays($q->created_at).' dagen niet bewerkt',
            ]);

        $actionItems = $sentNoResponse->concat($conceptExpiring)->concat($staleDrafts);

        // ── Statistieken ───────────────────────────────────────────────────
        $expiringSoon = Quote::whereIn('status', ['concept', 'verzonden'])
            ->whereNotNull('valid_until')
            ->whereDate('valid_until', '>=', today())
            ->whereDate('valid_until', '<=', today()->addDays(7))
            ->count();

        $waitingSignature = Quote::where('status', 'verzonden')->count();

        $signedThisMonth = Quote::where('status', 'ondertekend')
            ->whereYear('updated_at', now()->year)
            ->whereMonth('updated_at', now()->month)
            ->count();

        $totalOpen = Quote::whereIn('status', ['concept', 'verzonden'])->count();

        // ── Recente offertes ───────────────────────────────────────────────
        $recentQuotes = Quote::with(['customer', 'user'])->latest()->limit(8)->get();

        // ── Mijn openstaande taken ─────────────────────────────────────────
        $mijnTaken = Task::where('assigned_to_user_id', auth()->id())
            ->whereIn('status', ['open', 'in_behandeling'])
            ->orderBy('due_date')
            ->limit(5)
            ->with(['quote.customer'])
            ->get();

        return view('livewire.verkoper.dashboard.index', compact(
            'actionItems',
            'expiringSoon', 'waitingSignature', 'signedThisMonth', 'totalOpen',
            'recentQuotes', 'mijnTaken'
        ))->layout('layouts.app-verkoper', ['title' => 'Dashboard']);
    }
}
