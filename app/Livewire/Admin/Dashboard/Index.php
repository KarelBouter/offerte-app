<?php

namespace App\Livewire\Admin\Dashboard;

use App\Models\ActivityLog;
use App\Models\Quote;
use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        // ── Actie vereist (alle verkopers) ────────────────────────────────
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

        // ── Statistieken offertes ─────────────────────────────────────────
        $conceptCount  = Quote::where('status', 'concept')->count();
        $verzondCount  = Quote::where('status', 'verzonden')->count();
        $signedMonth   = Quote::where('status', 'ondertekend')
                              ->whereYear('updated_at', now()->year)
                              ->whereMonth('updated_at', now()->month)
                              ->count();
        $expiredMonth  = Quote::where('status', 'verlopen')
                              ->whereYear('updated_at', now()->year)
                              ->whereMonth('updated_at', now()->month)
                              ->count();

        // ── Statistieken omzet ────────────────────────────────────────────
        $pipelineOnetime = Quote::whereIn('status', ['concept', 'verzonden'])->sum('total_onetime_excl_vat');
        $pipelineYearly  = Quote::whereIn('status', ['concept', 'verzonden'])->sum('total_yearly_excl_vat');
        $wonOnetimeMonth = Quote::where('status', 'ondertekend')
                                ->whereYear('updated_at', now()->year)
                                ->whereMonth('updated_at', now()->month)
                                ->sum('total_onetime_excl_vat');
        $wonYearlyMonth  = Quote::where('status', 'ondertekend')
                                ->whereYear('updated_at', now()->year)
                                ->whereMonth('updated_at', now()->month)
                                ->sum('total_yearly_excl_vat');

        // ── Grafiekdata: laatste 6 maanden ────────────────────────────────
        $chartData = collect(range(5, 0))->map(function ($monthsBack) {
            $date  = now()->subMonths($monthsBack);
            $year  = $date->year;
            $month = $date->month;

            return [
                'label'   => $date->translatedFormat('M Y'),
                'created' => Quote::whereYear('created_at', $year)->whereMonth('created_at', $month)->count(),
                'signed'  => Quote::where('status', 'ondertekend')->whereYear('updated_at', $year)->whereMonth('updated_at', $month)->count(),
            ];
        });

        // ── Recente offertes ──────────────────────────────────────────────
        $recentQuotes = Quote::with(['customer', 'user'])->latest()->limit(6)->get();

        // ── Recente activiteit ────────────────────────────────────────────
        $recentActivity = ActivityLog::with('user')->orderByDesc('created_at')->limit(8)->get();

        return view('livewire.admin.dashboard.index', compact(
            'actionItems',
            'conceptCount', 'verzondCount', 'signedMonth', 'expiredMonth',
            'pipelineOnetime', 'pipelineYearly', 'wonOnetimeMonth', 'wonYearlyMonth',
            'chartData',
            'recentQuotes', 'recentActivity'
        ))->layout('layouts.app-admin', ['title' => 'Dashboard']);
    }
}
