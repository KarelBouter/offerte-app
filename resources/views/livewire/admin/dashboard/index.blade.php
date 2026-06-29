@php
    $statusColors = [
        'concept'     => 'bg-gray-100 text-gray-600',
        'verzonden'   => 'bg-blue-100 text-blue-700',
        'ondertekend' => 'bg-green-100 text-green-700',
        'verlopen'    => 'bg-orange-100 text-orange-700',
        'geannuleerd' => 'bg-red-100 text-red-700',
    ];
    $statusLabels = [
        'concept'     => 'Concept',
        'verzonden'   => 'Verzonden',
        'ondertekend' => 'Ondertekend',
        'verlopen'    => 'Verlopen',
        'geannuleerd' => 'Geannuleerd',
    ];
@endphp

<div class="space-y-6">

    {{-- ── Actie vereist ─────────────────────────────────────────────────── --}}
    @if($actionItems->isEmpty())
        <div class="flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 rounded-xl px-5 py-4">
            <svg class="w-5 h-5 flex-shrink-0 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="text-sm font-medium">Geen offertes vereisen op dit moment actie</span>
        </div>
    @else
        <div class="bg-white rounded-xl border-l-4 border-orange-400 border border-orange-200 overflow-hidden">
            <div class="px-5 py-3 bg-orange-50 border-b border-orange-200 flex items-center gap-2">
                <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                </svg>
                <h2 class="text-sm font-semibold text-orange-800">Vereist opvolging ({{ $actionItems->count() }})</h2>
            </div>
            <div class="overflow-x-auto">
            <table class="w-full text-sm min-w-[480px]">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide w-8">#</th>
                        <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Offerte</th>
                        <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Klant</th>
                        <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Verkoper</th>
                        <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Status</th>
                        <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Reden</th>
                        <th class="px-4 py-2.5 text-right text-xs font-semibold text-gray-500 uppercase tracking-wide">Actie</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($actionItems as $item)
                        @php $q = $item['quote']; @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-center">
                                <span class="inline-flex items-center justify-center w-5 h-5 rounded-full text-xs font-bold
                                    {{ $item['priority'] === 1 ? 'bg-red-100 text-red-700' : ($item['priority'] === 2 ? 'bg-orange-100 text-orange-700' : 'bg-yellow-100 text-yellow-700') }}">
                                    {{ $item['priority'] }}
                                </span>
                            </td>
                            <td class="px-4 py-3 font-mono text-xs font-medium text-gray-700">{{ $q->quote_number }}</td>
                            <td class="px-4 py-3 font-medium text-gray-800">{{ $q->customer?->company_name ?? '—' }}</td>
                            <td class="px-4 py-3 text-gray-500">{{ $q->user?->name ?? '—' }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $statusColors[$q->status] ?? '' }}">
                                    {{ $statusLabels[$q->status] ?? $q->status }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-500 text-xs">{{ $item['reason'] }}</td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('verkoper.offertes.show', $q) }}"
                                   class="text-xs font-medium text-blue-600 hover:text-blue-800">Bekijken</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
        </div>
    @endif

    {{-- ── Statistieken rij 1: offertes ──────────────────────────────────── --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Concept</p>
            <p class="text-3xl font-bold text-gray-700">{{ $conceptCount }}</p>
        </div>
        <div class="bg-white rounded-xl border border-blue-200 shadow-sm p-5">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Verzonden</p>
            <p class="text-3xl font-bold text-blue-600">{{ $verzondCount }}</p>
        </div>
        <div class="bg-white rounded-xl border border-green-200 shadow-sm p-5">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Ondertekend deze maand</p>
            <p class="text-3xl font-bold text-green-600">{{ $signedMonth }}</p>
        </div>
        <div class="bg-white rounded-xl border border-orange-200 shadow-sm p-5">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Verlopen deze maand</p>
            <p class="text-3xl font-bold text-orange-600">{{ $expiredMonth }}</p>
        </div>
    </div>

    {{-- ── Statistieken rij 2: omzet ──────────────────────────────────────── --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl border border-blue-100 shadow-sm p-5">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Pipeline eenmalig</p>
            <p class="text-2xl font-bold text-blue-700">€ {{ number_format($pipelineOnetime, 0, ',', '.') }}</p>
            <p class="text-xs text-gray-400 mt-1">Potentiële eenmalige omzet</p>
        </div>
        <div class="bg-white rounded-xl border border-blue-100 shadow-sm p-5">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Pipeline jaarlijks</p>
            <p class="text-2xl font-bold text-blue-700">€ {{ number_format($pipelineYearly, 0, ',', '.') }}</p>
            <p class="text-xs text-gray-400 mt-1">Potentiële jaaromzet servicecontracten</p>
        </div>
        <div class="bg-white rounded-xl border border-green-100 shadow-sm p-5">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Gewonnen eenmalig</p>
            <p class="text-2xl font-bold text-green-700">€ {{ number_format($wonOnetimeMonth, 0, ',', '.') }}</p>
            <p class="text-xs text-gray-400 mt-1">Ondertekend {{ now()->translatedFormat('F Y') }}</p>
        </div>
        <div class="bg-white rounded-xl border border-green-100 shadow-sm p-5">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Gewonnen jaarlijks</p>
            <p class="text-2xl font-bold text-green-700">€ {{ number_format($wonYearlyMonth, 0, ',', '.') }}</p>
            <p class="text-xs text-gray-400 mt-1">Ondertekend {{ now()->translatedFormat('F Y') }}</p>
        </div>
    </div>

    {{-- ── Grafiek offertes per maand ─────────────────────────────────────── --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5"
         x-data="{
             init() {
                 const data = {{ $chartData->toJson() }};
                 new Chart(this.$refs.canvas, {
                     type: 'bar',
                     data: {
                         labels: data.map(d => d.label),
                         datasets: [
                             {
                                 label: 'Aangemaakt',
                                 data: data.map(d => d.created),
                                 backgroundColor: 'rgba(156, 163, 175, 0.7)',
                                 borderRadius: 4,
                             },
                             {
                                 label: 'Ondertekend',
                                 data: data.map(d => d.signed),
                                 backgroundColor: 'rgba(34, 197, 94, 0.7)',
                                 borderRadius: 4,
                             },
                         ]
                     },
                     options: {
                         responsive: true,
                         maintainAspectRatio: false,
                         plugins: { legend: { position: 'bottom' } },
                         scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
                     }
                 });
             }
         }">
        <h2 class="text-sm font-semibold text-gray-700 mb-4">Offertes per maand — laatste 6 maanden</h2>
        <div class="h-52">
            <canvas x-ref="canvas"></canvas>
        </div>
    </div>

    {{-- ── Twee kolommen onderaan ──────────────────────────────────────────── --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

        {{-- Recente offertes --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-5 py-3 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
                <h2 class="text-sm font-semibold text-gray-700">Recente offertes</h2>
                <a href="{{ route('verkoper.offertes.index') }}" class="text-xs text-blue-600 hover:underline">Alle offertes →</a>
            </div>
            <div class="overflow-x-auto">
            <table class="w-full text-sm min-w-[480px]">
                <thead>
                    <tr class="border-b border-gray-100 text-left">
                        <th class="px-4 py-2 text-xs font-semibold text-gray-500">Nummer</th>
                        <th class="px-4 py-2 text-xs font-semibold text-gray-500">Klant</th>
                        <th class="px-4 py-2 text-xs font-semibold text-gray-500">Verkoper</th>
                        <th class="px-4 py-2 text-xs font-semibold text-gray-500">Status</th>
                        <th class="px-4 py-2 text-xs font-semibold text-gray-500">Datum</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($recentQuotes as $q)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2.5 font-mono text-xs font-medium">
                            <a href="{{ route('verkoper.offertes.show', $q) }}" class="text-blue-600 hover:underline">{{ $q->quote_number }}</a>
                        </td>
                        <td class="px-4 py-2.5 text-gray-700 text-xs">{{ $q->customer?->company_name ?? '—' }}</td>
                        <td class="px-4 py-2.5 text-gray-500 text-xs">{{ $q->user?->name ?? '—' }}</td>
                        <td class="px-4 py-2.5">
                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium {{ $statusColors[$q->status] ?? '' }}">
                                {{ $statusLabels[$q->status] ?? $q->status }}
                            </span>
                        </td>
                        <td class="px-4 py-2.5 text-gray-400 text-xs">{{ $q->created_at->format('d-m-Y') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-4 py-8 text-center text-gray-400 text-sm">Nog geen offertes.</td></tr>
                    @endforelse
                </tbody>
            </table>
            </div>
        </div>

        {{-- Recente activiteit + Openstaande taken --}}
        <div class="space-y-5">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-5 py-3 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
                    <h2 class="text-sm font-semibold text-gray-700">Recente activiteit</h2>
                    <a href="{{ route('beheer.activiteit.index') }}" class="text-xs text-blue-600 hover:underline">Alle activiteit →</a>
                </div>
                <ul class="divide-y divide-gray-50">
                    @forelse($recentActivity as $log)
                    <li class="px-5 py-3 flex items-start gap-3">
                        <div class="w-7 h-7 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                            <span class="text-xs font-bold text-blue-700">
                                {{ strtoupper(substr($log->user?->name ?? '?', 0, 1)) }}
                            </span>
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs text-gray-700 leading-snug">{{ $log->description }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">
                                {{ $log->user?->name ?? 'Systeem' }} &middot; {{ $log->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </li>
                    @empty
                    <li class="px-5 py-8 text-center text-gray-400 text-sm">Nog geen activiteit vastgelegd.</li>
                    @endforelse
                </ul>
            </div>

            {{-- Openstaande taken --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-5 py-3 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
                    <h2 class="text-sm font-semibold text-gray-700">Alle openstaande taken</h2>
                    <a href="{{ route('taken.index', ['tab' => 'alle']) }}" class="text-xs text-blue-600 hover:underline">Alle taken →</a>
                </div>
                @if($openTaken->isEmpty())
                    <p class="px-5 py-8 text-center text-gray-400 text-sm">Geen openstaande taken.</p>
                @else
                    <div class="overflow-x-auto">
                    <table class="w-full text-sm min-w-[480px]">
                        <tbody class="divide-y divide-gray-50">
                            @foreach($openTaken as $taak)
                            <tr class="hover:bg-gray-50">
                                <td class="px-5 py-2.5">
                                    <a href="{{ route('taken.show', $taak) }}"
                                       class="font-medium text-gray-800 hover:text-blue-600 text-xs">
                                        {{ $taak->title }}
                                    </a>
                                    @if($taak->quote)
                                        <p class="text-xs text-gray-400">
                                            {{ $taak->quote->quote_number }}
                                            @if($taak->quote->customer) — {{ $taak->quote->customer->company_name }} @endif
                                        </p>
                                    @endif
                                </td>
                                <td class="px-5 py-2.5 text-xs text-gray-500">
                                    {{ $taak->assignedTo?->name ?? '—' }}
                                </td>
                                <td class="px-5 py-2.5 text-xs text-right">
                                    @if($taak->due_date)
                                        <span class="{{ $taak->due_date->isPast() ? 'text-red-600 font-medium' : 'text-gray-400' }}">
                                            {{ $taak->due_date->format('d-m-Y') }}
                                        </span>
                                    @else
                                        <span class="text-gray-300">—</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
@endpush
