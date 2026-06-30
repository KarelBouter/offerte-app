<div>
 <x-breadcrumb :items="[['label' => 'Taken']]"/>

 {{-- Tabs --}}
 <div class="flex items-center gap-1 mb-5 bg-gray-100 rounded-lg p-1 w-fit">
 <button wire:click="$set('tab', 'mijn')"
 class="px-4 py-1.5 rounded-md text-sm font-medium transition-colors
 {{ $tab === 'mijn' ? 'bg-white text-gray-800 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
 Mijn taken
 </button>
 <button wire:click="$set('tab', 'aangemaakt')"
 class="px-4 py-1.5 rounded-md text-sm font-medium transition-colors
 {{ $tab === 'aangemaakt' ? 'bg-white text-gray-800 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
 Aangemaakt door mij
 </button>
 @if($isAdmin)
 <button wire:click="$set('tab', 'alle')"
 class="px-4 py-1.5 rounded-md text-sm font-medium transition-colors
 {{ $tab === 'alle' ? 'bg-white text-gray-800 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
 Alle taken
 </button>
 @endif
 </div>

 {{-- Filters --}}
 <div class="flex flex-wrap items-center gap-3 mb-5">
 <input wire:model.live.debounce.300ms="search" type="text"
 placeholder="Zoek op taaknaam…"
 class="w-full sm:w-64 rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"/>

 <select wire:model.live="statusFilter"
 class="rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
 <option value="">Alle statussen</option>
 @foreach($statusLabels as $val => $label)
 <option value="{{ $val }}">{{ $label }}</option>
 @endforeach
 </select>

 <select wire:model.live="quoteFilter"
 class="rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
 <option value="">Alle offertes</option>
 @foreach($quotes as $q)
 <option value="{{ $q['id'] }}">{{ $q['label'] }}</option>
 @endforeach
 </select>

 <button onclick="Livewire.dispatch('open-task-modal')"
 class="ml-auto inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium text-white shadow-sm"
 style="background-color: #1B3A6B;">
 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
 </svg>
 Nieuwe taak
 </button>
 </div>

 {{-- Tabel --}}
 <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
 <div class="overflow-x-auto">
 <table class="w-full text-sm">
 <thead>
 <tr class="bg-gray-50 border-b border-gray-200 text-left">
 <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Status</th>
 <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Taak</th>
 <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide hidden sm:table-cell">Gekoppeld aan</th>
 <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide hidden md:table-cell">Toegewezen aan</th>
 <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide hidden lg:table-cell">Deadline</th>
 <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide hidden lg:table-cell">Aangemaakt door</th>
 <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide text-right">Acties</th>
 </tr>
 </thead>
 <tbody class="divide-y divide-gray-100">
 @forelse($tasks as $task)
 @php
 $statusColors = ['open'=>'bg-gray-100 text-gray-600','in_behandeling'=>'bg-blue-100 text-blue-700','afgerond'=>'bg-green-100 text-green-700'];
 $isOwner = $task->created_by_user_id === auth()->id();
 $canDelete = $isAdmin || $isOwner;
 @endphp
 <tr class="hover:bg-gray-50 transition-colors {{ $task->status === 'afgerond' ? 'opacity-60' : '' }}">
 <td class="px-5 py-3.5 whitespace-nowrap">
 <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $statusColors[$task->status] ?? 'bg-gray-100 text-gray-600' }}">
 {{ $statusLabels[$task->status] ?? $task->status }}
 </span>
 </td>
 <td class="px-5 py-3.5 whitespace-nowrap">
 <a href="{{ route('taken.show', $task) }}"
 class="font-medium text-gray-800 hover:text-blue-600 {{ $task->status === 'afgerond' ? 'line-through' : '' }}">
 {{ $task->title }}
 </a>
 </td>
 <td class="px-5 py-3.5 text-gray-500 whitespace-nowrap hidden sm:table-cell">
 @if($task->quote)
 <a href="{{ route('verkoper.offertes.show', $task->quote) }}"
 class="text-blue-600 hover:text-blue-800 text-xs font-mono">
 {{ $task->quote->quote_number }}
 </a>
 @else
 <span class="text-gray-300">—</span>
 @endif
 </td>
 <td class="px-5 py-3.5 text-gray-600 whitespace-nowrap hidden md:table-cell">{{ $task->assignedTo?->name ?? '—' }}</td>
 <td class="px-5 py-3.5 whitespace-nowrap hidden lg:table-cell">
 @if($task->due_date)
 <span class="{{ $task->due_date->isPast() && $task->status !== 'afgerond' ? 'text-red-600 font-medium' : ($task->due_date->diffInDays(now()) <= 2 && $task->status !== 'afgerond' ? 'text-orange-600' : 'text-gray-500') }}">
 {{ $task->due_date->format('d-m-Y') }}
 </span>
 @else
 <span class="text-gray-300">—</span>
 @endif
 </td>
 <td class="px-5 py-3.5 text-gray-500 whitespace-nowrap hidden lg:table-cell">{{ $task->createdBy?->name ?? '—' }}</td>
 <td class="px-5 py-3.5 text-right whitespace-nowrap">
 <div class="flex items-center justify-end gap-2">
 <a href="{{ route('taken.show', $task) }}"
 class="text-blue-600 hover:text-blue-800 text-xs font-medium">Bekijken</a>

 <select wire:change="changeStatus({{ $task->id }}, $event.target.value)"
 class="text-xs border-gray-200 rounded-md py-1 text-gray-600 focus:ring-blue-500">
 <option value="open" {{ $task->status === 'open' ? 'selected' : '' }}>Open</option>
 <option value="in_behandeling" {{ $task->status === 'in_behandeling' ? 'selected' : '' }}>In behandeling</option>
 <option value="afgerond" {{ $task->status === 'afgerond' ? 'selected' : '' }}>Afgerond</option>
 </select>

 @if($canDelete)
 <button wire:click="prepareConfirmDelete({{ $task->id }}, '{{ addslashes($task->title) }}')"
 class="text-red-400 hover:text-red-600 text-xs font-medium">
 Verwijderen
 </button>
 @endif
 </div>
 </td>
 </tr>
 @empty
 <tr>
 <td colspan="7" class="px-5 py-16 text-center">
 <svg class="w-10 h-10 mx-auto mb-3 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
 </svg>
 <p class="text-sm font-medium text-gray-500 mb-3">Geen taken gevonden</p>
 <button onclick="Livewire.dispatch('open-task-modal')"
 class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium text-white"
 style="background-color: #1B3A6B;">
 Nieuwe taak aanmaken
 </button>
 </td>
 </tr>
 @endforelse
 </tbody>
 </table>
 </div>
 </div>

<x-confirm-modal name="confirm-task"
    title="Taak verwijderen?"
    :message="'Wil je \'' . $confirmingTitle . '\' definitief verwijderen?'"
    variant="danger">
    <button wire:click="deleteTask({{ $confirmingId ?? 0 }})"
            class="px-4 py-2 rounded-lg text-sm font-medium text-white bg-red-600 hover:bg-red-700 transition-colors">
        Verwijderen
    </button>
</x-confirm-modal>
</div>
