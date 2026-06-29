@php
 $statusColors = [
 'concept' => 'bg-gray-100 text-gray-600',
 'verzonden' => 'bg-blue-100 text-blue-700',
 'ondertekend' => 'bg-green-100 text-green-700',
 'verlopen' => 'bg-orange-100 text-orange-700',
 'geannuleerd' => 'bg-red-100 text-red-700',
 ];
 $statusLabels = [
 'concept' => 'Concept',
 'verzonden' => 'Verzonden',
 'ondertekend' => 'Ondertekend',
 'verlopen' => 'Verlopen',
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
 <div class="bg-white rounded-xl border-l-4 border-orange-400 border border-orange-200">
 <div class="px-5 py-3 bg-orange-50 border-b border-orange-200 flex items-center gap-2">
 <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
 </svg>
 <h2 class="text-sm font-semibold text-orange-800">Vereist opvolging ({{ $actionItems->count() }})</h2>
 </div>
 <div class="overflow-x-auto">
 <table class="w-full text-sm min-w-[600px]">
 <thead class="bg-gray-50 border-b border-gray-100">
 <tr>
 <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide w-8">#</th>
 <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Offerte</th>
 <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Klant</th>
 <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Status</th>
 <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Reden</th>
 <th class="px-4 py-2.5 text-right text-xs font-semibold text-gray-500 uppercase tracking-wide">Actie</th>
 </tr>
 </thead>
 <tbody class="divide-y divide-gray-100">
 @foreach($actionItems as $item)
 @php $q = $item['quote']; @endphp
 <tr class="hover:bg-gray-50">
 <td class="px-4 py-3 text-center whitespace-nowrap">
 <span class="inline-flex items-center justify-center w-5 h-5 rounded-full text-xs font-bold
 {{ $item['priority'] === 1 ? 'bg-red-100 text-red-700' : ($item['priority'] === 2 ? 'bg-orange-100 text-orange-700' : 'bg-yellow-100 text-yellow-700') }}">
 {{ $item['priority'] }}
 </span>
 </td>
 <td class="px-4 py-3 font-mono text-xs font-medium text-gray-700 whitespace-nowrap">{{ $q->quote_number }}</td>
 <td class="px-4 py-3 font-medium text-gray-800 whitespace-nowrap">{{ $q->customer?->company_name ?? '—' }}</td>
 <td class="px-4 py-3 whitespace-nowrap">
 <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $statusColors[$q->status] ?? '' }}">
 {{ $statusLabels[$q->status] ?? $q->status }}
 </span>
 </td>
 <td class="px-4 py-3 text-gray-500 text-xs whitespace-nowrap">{{ $item['reason'] }}</td>
 <td class="px-4 py-3 text-right whitespace-nowrap">
 <div class="flex items-center justify-end gap-2">
 <a href="{{ route('verkoper.offertes.show', $q) }}"
 class="text-xs font-medium text-blue-600 hover:text-blue-800">Bekijken</a>
 @if($q->status === 'concept')
 <a href="{{ route('verkoper.offertes.edit', $q) }}"
 class="text-xs font-medium text-gray-500 hover:text-gray-700">Bewerken</a>
 @endif
 </div>
 </td>
 </tr>
 @endforeach
 </tbody>
 </table>
 </div>
 </div>
 @endif

 {{-- ── Statistieken tegels ─────────────────────────────────────────────── --}}
 <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
 <a href="{{ route('verkoper.offertes.index', ['filter' => 'bijna_verlopen']) }}"
 class="bg-white rounded-xl border border-orange-200 shadow-sm p-5 hover:border-orange-300 transition-colors">
 <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Bijna verlopen</p>
 <p class="text-3xl font-bold text-orange-600">{{ $expiringSoon }}</p>
 <p class="text-xs text-gray-400 mt-1">Verlopen binnen 7 dagen</p>
 </a>

 <a href="{{ route('verkoper.offertes.index', ['status' => 'verzonden']) }}"
 class="bg-white rounded-xl border border-blue-200 shadow-sm p-5 hover:border-blue-300 transition-colors">
 <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Wacht op ondertekening</p>
 <p class="text-3xl font-bold text-blue-600">{{ $waitingSignature }}</p>
 <p class="text-xs text-gray-400 mt-1">Verstuurd, nog niet ondertekend</p>
 </a>

 <a href="{{ route('verkoper.offertes.index', ['filter' => 'ondertekend_deze_maand']) }}"
 class="bg-white rounded-xl border border-green-200 shadow-sm p-5 hover:border-green-300 transition-colors">
 <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Geaccepteerd</p>
 <p class="text-3xl font-bold text-green-600">{{ $signedThisMonth }}</p>
 <p class="text-xs text-gray-400 mt-1">Ondertekend in {{ now()->translatedFormat('F Y') }}</p>
 </a>

 <a href="{{ route('verkoper.offertes.index') }}"
 class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 hover:border-gray-300 transition-colors">
 <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Totaal open</p>
 <p class="text-3xl font-bold text-gray-700">{{ $totalOpen }}</p>
 <p class="text-xs text-gray-400 mt-1">Alle openstaande offertes</p>
 </a>
 </div>

 {{-- ── Mijn openstaande taken ─────────────────────────────────────────── --}}
 @if($mijnTaken->isNotEmpty())
 <div class="bg-white rounded-xl shadow-sm border border-gray-200">
 <div class="px-5 py-3 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
 <h2 class="text-sm font-semibold text-gray-700">Mijn openstaande taken</h2>
 <a href="{{ route('taken.index') }}" class="text-xs text-blue-600 hover:underline">Alle taken →</a>
 </div>
 <div class="overflow-x-auto">
 <table class="w-full text-sm min-w-[600px]">
 <thead>
 <tr class="border-b border-gray-100 text-left">
 <th class="px-5 py-2 text-xs font-semibold text-gray-500">Taak</th>
 <th class="px-5 py-2 text-xs font-semibold text-gray-500">Gekoppeld aan</th>
 <th class="px-5 py-2 text-xs font-semibold text-gray-500">Deadline</th>
 <th class="px-5 py-2 text-xs font-semibold text-gray-500 text-right">Actie</th>
 </tr>
 </thead>
 <tbody class="divide-y divide-gray-50">
 @foreach($mijnTaken as $taak)
 <tr class="hover:bg-gray-50">
 <td class="px-5 py-3 font-medium text-gray-800 whitespace-nowrap">{{ $taak->title }}</td>
 <td class="px-5 py-3 text-gray-500 text-xs whitespace-nowrap">
 @if($taak->quote)
 <a href="{{ route('verkoper.offertes.show', $taak->quote) }}"
 class="text-blue-600 hover:underline font-mono">
 {{ $taak->quote->quote_number }}
 @if($taak->quote->customer)
 — {{ $taak->quote->customer->company_name }}
 @endif
 </a>
 @else
 <span class="text-gray-300">—</span>
 @endif
 </td>
 <td class="px-5 py-3 text-xs whitespace-nowrap">
 @if($taak->due_date)
 <span class="{{ $taak->due_date->isPast() ? 'text-red-600 font-medium' : ($taak->due_date->diffInDays(now()) <= 2 ? 'text-orange-600' : 'text-gray-500') }}">
 {{ $taak->due_date->format('d-m-Y') }}
 </span>
 @else
 <span class="text-gray-300">—</span>
 @endif
 </td>
 <td class="px-5 py-3 text-right whitespace-nowrap">
 <a href="{{ route('taken.show', $taak) }}"
 class="text-xs font-medium text-blue-600 hover:text-blue-800">Bekijken</a>
 </td>
 </tr>
 @endforeach
 </tbody>
 </table>
 </div>
 </div>
 @endif

 {{-- ── Recente offertes ────────────────────────────────────────────────── --}}
 <div class="bg-white rounded-xl shadow-sm border border-gray-200">
 <div class="px-5 py-3 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
 <h2 class="text-sm font-semibold text-gray-700">Recente offertes</h2>
 <a href="{{ route('verkoper.offertes.index') }}" class="text-xs text-blue-600 hover:underline">Alle offertes →</a>
 </div>
 <div class="overflow-x-auto">
 <table class="w-full text-sm min-w-[600px]">
 <thead>
 <tr class="border-b border-gray-100 text-left">
 <th class="px-5 py-2.5 text-xs font-semibold text-gray-500">Nummer</th>
 <th class="px-5 py-2.5 text-xs font-semibold text-gray-500">Klant</th>
 <th class="px-5 py-2.5 text-xs font-semibold text-gray-500">Verkoper</th>
 <th class="px-5 py-2.5 text-xs font-semibold text-gray-500">Status</th>
 <th class="px-5 py-2.5 text-xs font-semibold text-gray-500">Datum</th>
 <th class="px-5 py-2.5 text-xs font-semibold text-gray-500 text-right">Totaal eenmalig</th>
 <th class="px-5 py-2.5 text-xs font-semibold text-gray-500 text-right">Actie</th>
 </tr>
 </thead>
 <tbody class="divide-y divide-gray-50">
 @forelse($recentQuotes as $q)
 <tr class="hover:bg-gray-50">
 <td class="px-5 py-3 font-mono text-xs font-medium text-gray-700 whitespace-nowrap">{{ $q->quote_number }}</td>
 <td class="px-5 py-3 font-medium text-gray-800 whitespace-nowrap">{{ $q->customer?->company_name ?? '—' }}</td>
 <td class="px-5 py-3 text-gray-500 whitespace-nowrap">{{ $q->user?->name ?? '—' }}</td>
 <td class="px-5 py-3 whitespace-nowrap">
 <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $statusColors[$q->status] ?? 'bg-gray-100 text-gray-600' }}">
 {{ $statusLabels[$q->status] ?? $q->status }}
 </span>
 </td>
 <td class="px-5 py-3 text-gray-400 whitespace-nowrap">{{ $q->created_at->format('d-m-Y') }}</td>
 <td class="px-5 py-3 text-right text-gray-700 whitespace-nowrap">€ {{ number_format($q->total_onetime_excl_vat, 2, ',', '.') }}</td>
 <td class="px-5 py-3 text-right whitespace-nowrap">
 <a href="{{ route('verkoper.offertes.show', $q) }}" class="text-xs font-medium text-blue-600 hover:text-blue-800">Bekijken</a>
 </td>
 </tr>
 @empty
 <tr>
 <td colspan="7" class="px-5 py-14 text-center">
 <svg class="w-10 h-10 mx-auto mb-3 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
 </svg>
 <p class="text-sm font-medium text-gray-500 mb-3">Nog geen offertes aangemaakt</p>
 <a href="{{ route('verkoper.offertes.create') }}"
 class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium text-white"
 style="background-color: #1B3A6B;">
 Maak je eerste offerte aan
 </a>
 </td>
 </tr>
 @endforelse
 </tbody>
 </table>
 </div>
 </div>
</div>
