<?php

namespace App\Livewire\Tasks;

use App\Models\Quote;
use App\Models\Task;
use App\Models\User;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Livewire\Attributes\On;
use Livewire\Component;

class Index extends Component
{
    public string $tab          = 'mijn';
    public string $statusFilter = '';
    public ?int   $quoteFilter  = null;
    public string $search       = '';

    public const STATUS_LABELS = [
        'open'          => 'Open',
        'in_behandeling' => 'In behandeling',
        'afgerond'      => 'Afgerond',
    ];

    public function mount(Request $request): void
    {
        $this->tab = $request->get('tab', 'mijn');
    }

    #[On('task-saved')]
    public function handleTaskSaved(): void {}

    public function deleteTask(int $id): void
    {
        $task = Task::findOrFail($id);
        $isAdmin = auth()->user()->role === 'admin';
        $isOwner = $task->created_by_user_id === auth()->id();

        if (!$isAdmin && !$isOwner) {
            return;
        }

        app(ActivityLogService::class)->log(
            'task.deleted',
            $task,
            'Taak "' . $task->title . '" verwijderd'
        );

        $task->delete();
        session()->flash('success', 'Taak verwijderd.');
    }

    public function changeStatus(int $id, string $status): void
    {
        $task = Task::findOrFail($id);
        $task->update(['status' => $status]);
    }

    public function render()
    {
        $userId  = auth()->id();
        $isAdmin = auth()->user()->role === 'admin';

        $query = Task::with(['createdBy', 'assignedTo', 'quote.customer'])
            ->when($this->search, fn ($q) => $q->where('title', 'like', '%' . $this->search . '%'))
            ->when($this->statusFilter, fn ($q) => $q->where('status', $this->statusFilter))
            ->when($this->quoteFilter, fn ($q) => $q->where('quote_id', $this->quoteFilter));

        if ($this->tab === 'mijn') {
            $query->where('assigned_to_user_id', $userId);
        } elseif ($this->tab === 'aangemaakt') {
            $query->where('created_by_user_id', $userId);
        }

        $tasks  = $query->orderByRaw("CASE status WHEN 'open' THEN 0 WHEN 'in_behandeling' THEN 1 ELSE 2 END")->orderBy('due_date')->get();
        $quotes = Quote::with('customer')->orderByDesc('id')->get(['id', 'quote_number', 'customer_id'])->map(fn ($q) => [
            'id'    => $q->id,
            'label' => $q->quote_number,
        ]);

        $layout = $isAdmin ? 'layouts.app-admin' : 'layouts.app-verkoper';

        return view('livewire.tasks.index', [
            'tasks'        => $tasks,
            'quotes'       => $quotes,
            'isAdmin'      => $isAdmin,
            'statusLabels' => self::STATUS_LABELS,
        ])->layout($layout, ['title' => 'Taken']);
    }
}
