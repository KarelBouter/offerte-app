<?php

namespace App\Livewire\Tasks;

use App\Models\Quote;
use App\Models\Task;
use App\Models\TaskMention;
use App\Models\User;
use App\Services\ActivityLogService;
use App\Services\NotificationService;
use Livewire\Attributes\On;
use Livewire\Component;

class Modal extends Component
{
    public bool $isOpen = false;

    // Formuliervelden
    public ?int    $taskId             = null;
    public string  $title              = '';
    public string  $description        = '';
    public ?int    $assignedToUserId   = null;
    public ?int    $quoteId            = null;
    public string  $quoteSearch        = '';
    public string  $selectedQuoteLabel = '';
    public array   $quoteSuggestions   = [];
    public string  $dueDate            = '';
    public string  $status             = 'open';

    #[On('open-task-modal')]
    public function open(?int $quoteId = null, ?int $taskId = null): void
    {
        $this->reset(['title', 'description', 'assignedToUserId', 'quoteSearch', 'selectedQuoteLabel',
                      'quoteSuggestions', 'dueDate']);
        $this->status   = 'open';
        $this->taskId   = $taskId;
        $this->quoteId  = $quoteId;

        if ($quoteId) {
            $quote = Quote::with('customer')->find($quoteId);
            if ($quote) {
                $this->selectedQuoteLabel = $quote->quote_number . ' — ' . ($quote->customer?->company_name ?? '');
            }
        }

        if ($taskId) {
            $task = Task::find($taskId);
            if ($task) {
                $this->title            = $task->title;
                $this->description      = $task->description ?? '';
                $this->assignedToUserId = $task->assigned_to_user_id;
                $this->quoteId          = $task->quote_id;
                $this->dueDate          = $task->due_date?->format('Y-m-d') ?? '';
                $this->status           = $task->status;

                if ($task->quote_id) {
                    $quote = Quote::with('customer')->find($task->quote_id);
                    if ($quote) {
                        $this->selectedQuoteLabel = $quote->quote_number . ' — ' . ($quote->customer?->company_name ?? '');
                    }
                }
            }
        }

        $this->assignedToUserId ??= auth()->id();
        $this->isOpen = true;
    }

    public function close(): void
    {
        $this->isOpen = false;
    }

    public function updatedQuoteSearch(): void
    {
        if (strlen($this->quoteSearch) < 2) {
            $this->quoteSuggestions = [];
            return;
        }

        $this->quoteSuggestions = Quote::with('customer')
            ->where(function ($q) {
                $q->where('quote_number', 'like', '%' . $this->quoteSearch . '%')
                  ->orWhereHas('customer', fn ($q) => $q->where('company_name', 'like', '%' . $this->quoteSearch . '%'));
            })
            ->limit(8)
            ->get()
            ->map(fn ($q) => [
                'id'    => $q->id,
                'label' => $q->quote_number . ' — ' . ($q->customer?->company_name ?? ''),
            ])
            ->toArray();
    }

    public function selectQuote(int $id, string $label): void
    {
        $this->quoteId            = $id;
        $this->selectedQuoteLabel = $label;
        $this->quoteSearch        = '';
        $this->quoteSuggestions   = [];
    }

    public function clearQuote(): void
    {
        $this->quoteId            = null;
        $this->selectedQuoteLabel = '';
    }

    public function save(): void
    {
        $this->validate([
            'title'           => 'required|string|max:255',
            'description'     => 'nullable|string',
            'assignedToUserId' => 'nullable|exists:users,id',
            'quoteId'         => 'nullable|exists:quotes,id',
            'dueDate'         => 'nullable|date',
            'status'          => 'required|in:open,in_behandeling,afgerond',
        ], [
            'title.required' => 'Taaknaam is verplicht.',
        ]);

        $isNew = $this->taskId === null;

        $task = $isNew
            ? new Task(['created_by_user_id' => auth()->id()])
            : Task::findOrFail($this->taskId);

        $task->fill([
            'title'               => $this->title,
            'description'         => $this->description ?: null,
            'assigned_to_user_id' => $this->assignedToUserId ?: null,
            'quote_id'            => $this->quoteId,
            'due_date'            => $this->dueDate ?: null,
            'status'              => $this->status,
        ]);
        $task->save();
        $task->load(['createdBy', 'assignedTo', 'quote']);

        if ($isNew) {
            // @-mentions verwerken
            if ($task->description) {
                $mentionedUsers = $task->extractMentions($task->description);
                foreach ($mentionedUsers as $user) {
                    TaskMention::firstOrCreate(['task_id' => $task->id, 'user_id' => $user->id]);
                    app(NotificationService::class)->notifyTaskMentioned($task, $user);
                }
            }

            // Toewijzingsnotificatie
            app(NotificationService::class)->notifyTaskAssigned($task);

            app(ActivityLogService::class)->log(
                'task.created',
                $task,
                'Taak "' . $task->title . '" aangemaakt'
            );

            if ($task->assigned_to_user_id && $task->assigned_to_user_id !== auth()->id()) {
                app(ActivityLogService::class)->log(
                    'task.assigned',
                    $task,
                    'Taak "' . $task->title . '" toegewezen aan ' . ($task->assignedTo?->name ?? '—')
                );
            }
        }

        $this->isOpen = false;
        $this->dispatch('task-saved');
        session()->flash('success', $isNew ? 'Taak aangemaakt.' : 'Taak bijgewerkt.');
    }

    public function render()
    {
        $users = User::where('is_active', true)->orderBy('name')->get(['id', 'name'])->toArray();

        return view('livewire.tasks.modal', compact('users'));
    }
}
