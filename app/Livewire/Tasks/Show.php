<?php

namespace App\Livewire\Tasks;

use App\Models\Task;
use App\Services\ActivityLogService;
use App\Services\NotificationService;
use Livewire\Attributes\On;
use Livewire\Component;

class Show extends Component
{
    public Task $task;

    public function mount(Task $task): void
    {
        $this->task = $task->load(['createdBy', 'assignedTo', 'quote.customer', 'mentions.user']);
    }

    #[On('task-saved')]
    public function handleTaskSaved(): void
    {
        $this->task->refresh();
        $this->task->load(['createdBy', 'assignedTo', 'quote.customer', 'mentions.user']);
    }

    public function complete(): void
    {
        app(NotificationService::class)->notifyTaskCompleted($this->task);
        $this->task->complete();
        app(ActivityLogService::class)->log(
            'task.completed',
            $this->task,
            'Taak "' . $this->task->title . '" afgerond'
        );
        session()->flash('success', 'Taak gemarkeerd als afgerond.');
    }

    public function delete(): void
    {
        $isAdmin = auth()->user()->role === 'admin';
        $isOwner = $this->task->created_by_user_id === auth()->id();

        if (!$isAdmin && !$isOwner) {
            return;
        }

        app(ActivityLogService::class)->log(
            'task.deleted',
            $this->task,
            'Taak "' . $this->task->title . '" verwijderd'
        );

        $this->task->delete();
        $this->redirect(route('taken.index'));
    }

    public function render()
    {
        $isAdmin = auth()->user()->role === 'admin';
        $layout  = $isAdmin ? 'layouts.app-admin' : 'layouts.app-verkoper';

        return view('livewire.tasks.show', [
            'isAdmin' => $isAdmin,
            'isOwner' => $this->task->created_by_user_id === auth()->id(),
        ])->layout($layout, ['title' => $this->task->title]);
    }
}
