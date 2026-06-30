<div>
    <x-breadcrumb :items="[
        ['label' => 'Taken', 'route' => 'taken.index'],
        ['label' => $task->title]
    ]"/>

    <div class="max-w-2xl space-y-5">

        {{-- Header --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <div class="flex items-start justify-between gap-4">
                <div class="flex-1 min-w-0">
                    <h1 class="text-xl font-bold text-gray-900 {{ $task->status === 'afgerond' ? 'line-through text-gray-400' : '' }}">
                        {{ $task->title }}
                    </h1>
                    @if($task->description)
                        <div class="mt-3 text-sm text-gray-600 whitespace-pre-line leading-relaxed">
                            {!! preg_replace('/@(\w+)/', '<span class="text-blue-600 font-medium">@$1</span>', e($task->description)) !!}
                        </div>
                    @endif
                </div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium flex-shrink-0
                    {{ $task->status === 'open' ? 'bg-gray-100 text-gray-600' : ($task->status === 'in_behandeling' ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700') }}">
                    {{ ['open' => 'Open', 'in_behandeling' => 'In behandeling', 'afgerond' => 'Afgerond'][$task->status] ?? $task->status }}
                </span>
            </div>
        </div>

        {{-- Meta --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <dl class="grid grid-cols-2 gap-x-6 gap-y-4 text-sm">
                <div>
                    <dt class="text-gray-500 font-medium mb-0.5">Aangemaakt door</dt>
                    <dd class="text-gray-800">{{ $task->createdBy?->name ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500 font-medium mb-0.5">Toegewezen aan</dt>
                    <dd class="text-gray-800">{{ $task->assignedTo?->name ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500 font-medium mb-0.5">Aangemaakt op</dt>
                    <dd class="text-gray-800">{{ $task->created_at->format('d-m-Y H:i') }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500 font-medium mb-0.5">Laatste wijziging</dt>
                    <dd class="text-gray-800">{{ $task->updated_at->diffForHumans() }}</dd>
                </div>
                @if($task->due_date)
                <div>
                    <dt class="text-gray-500 font-medium mb-0.5">Deadline</dt>
                    <dd class="{{ $task->due_date->isPast() && $task->status !== 'afgerond' ? 'text-red-600 font-semibold' : ($task->due_date->diffInDays(now()) <= 2 && $task->status !== 'afgerond' ? 'text-orange-600 font-semibold' : 'text-gray-800') }}">
                        {{ $task->due_date->format('d-m-Y') }}
                        @if($task->due_date->isPast() && $task->status !== 'afgerond') (verlopen) @endif
                        @if(!$task->due_date->isPast() && $task->due_date->diffInDays(now()) <= 2 && $task->status !== 'afgerond')
                            (over {{ $task->due_date->diffInDays(now()) }} dag{{ $task->due_date->diffInDays(now()) !== 1 ? 'en' : '' }})
                        @endif
                    </dd>
                </div>
                @endif
                @if($task->quote)
                <div>
                    <dt class="text-gray-500 font-medium mb-0.5">Gekoppelde offerte</dt>
                    <dd>
                        <a href="{{ route('verkoper.offertes.show', $task->quote) }}"
                           class="text-blue-600 hover:text-blue-800 font-mono text-xs font-medium">
                            {{ $task->quote->quote_number }}
                            @if($task->quote->customer)
                                — {{ $task->quote->customer->company_name }}
                            @endif
                        </a>
                    </dd>
                </div>
                @endif
                @if($task->completed_at)
                <div>
                    <dt class="text-gray-500 font-medium mb-0.5">Afgerond op</dt>
                    <dd class="text-green-700">{{ $task->completed_at->format('d-m-Y H:i') }}</dd>
                </div>
                @endif
            </dl>
        </div>

        {{-- Acties --}}
        <div class="flex items-center gap-3">
            @if($task->status !== 'afgerond')
                <button wire:click="complete"
                        class="flex-1 flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg text-sm font-medium text-white bg-green-600 hover:bg-green-700 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Markeer als afgerond
                </button>
            @endif

            @if($isAdmin || $isOwner)
                <button onclick="Livewire.dispatch('open-task-modal', { taskId: {{ $task->id }} })"
                        class="px-4 py-2.5 rounded-lg text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 transition-colors">
                    Bewerken
                </button>

                <button x-on:click="$dispatch('open-modal', 'confirm-delete-task')"
                        class="px-4 py-2.5 rounded-lg text-sm font-medium text-red-600 bg-red-50 hover:bg-red-100 transition-colors">
                    Verwijderen
                </button>
            @endif
        </div>
    </div>
</div>

<x-confirm-modal name="confirm-delete-task"
    title="Taak definitief verwijderen?"
    :message="'Wil je \'' . $task->title . '\' definitief verwijderen? Dit kan niet ongedaan worden gemaakt.'"
    variant="danger">
    <button wire:click="delete"
            class="px-4 py-2 rounded-lg text-sm font-medium text-white bg-red-600 hover:bg-red-700 transition-colors">
        Verwijderen
    </button>
</x-confirm-modal>
