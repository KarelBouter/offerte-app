<?php

namespace App\Livewire\Admin\Settings;

use App\Models\AutoTaskTemplate;
use App\Models\User;
use Livewire\Component;

class AutoTaskTemplates extends Component
{
    public const TRIGGER_LABELS = [
        'verzonden'   => 'Verzonden',
        'ondertekend' => 'Ondertekend',
        'verlopen'    => 'Verlopen',
        'geannuleerd' => 'Geannuleerd',
    ];

    public const TRIGGER_COLORS = [
        'verzonden'   => 'bg-blue-100 text-blue-700',
        'ondertekend' => 'bg-green-100 text-green-700',
        'verlopen'    => 'bg-orange-100 text-orange-700',
        'geannuleerd' => 'bg-red-100 text-red-700',
    ];

    public const VARS = [
        '{{klant}}'           => 'Klantnaam',
        '{{offerte_nr}}'      => 'Offertenummer',
        '{{bedrag_eenmalig}}' => 'Bedrag eenmalig',
        '{{bedrag_jaarlijks}}' => 'Bedrag jaarlijks',
        '{{verkoper}}'        => 'Verkoper',
    ];

    // Modal state
    public bool   $showModal          = false;
    public ?int   $editingId          = null;

    // Confirm delete
    public ?int   $confirmingId       = null;
    public string $confirmingName     = '';

    // Form fields
    public string $name               = '';
    public string $trigger_status     = 'ondertekend';
    public string $title_template     = '';
    public string $description_template = '';
    public string $assign_to_user_id  = '';
    public string $due_days           = '';
    public bool   $is_active          = true;

    public function openCreate(): void
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function openEdit(int $id): void
    {
        $t = AutoTaskTemplate::findOrFail($id);
        $this->editingId              = $id;
        $this->name                   = $t->name;
        $this->trigger_status         = $t->trigger_status;
        $this->title_template         = $t->title_template;
        $this->description_template   = $t->description_template ?? '';
        $this->assign_to_user_id      = (string) ($t->assign_to_user_id ?? '');
        $this->due_days               = $t->due_days !== null ? (string) $t->due_days : '';
        $this->is_active              = $t->is_active;
        $this->resetValidation();
        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function save(): void
    {
        $this->validate([
            'name'             => 'required|string|max:255',
            'trigger_status'   => 'required|in:verzonden,ondertekend,verlopen,geannuleerd',
            'title_template'   => 'required|string|max:255',
            'description_template' => 'nullable|string|max:2000',
            'assign_to_user_id' => 'nullable|exists:users,id',
            'due_days'         => 'nullable|integer|min:0|max:365',
        ], [
            'name.required'           => 'Naam is verplicht.',
            'trigger_status.required' => 'Triggerstatus is verplicht.',
            'title_template.required' => 'Taaknaam is verplicht.',
            'due_days.integer'        => 'Aantal dagen moet een getal zijn.',
            'due_days.min'            => 'Aantal dagen moet 0 of meer zijn.',
        ]);

        $maxOrder = AutoTaskTemplate::max('sort_order') ?? 0;

        $data = [
            'name'                 => $this->name,
            'trigger_status'       => $this->trigger_status,
            'title_template'       => $this->title_template,
            'description_template' => $this->description_template ?: null,
            'assign_to_user_id'    => $this->assign_to_user_id !== '' ? (int) $this->assign_to_user_id : null,
            'due_days'             => $this->due_days !== '' ? (int) $this->due_days : null,
            'is_active'            => $this->is_active,
        ];

        if ($this->editingId) {
            AutoTaskTemplate::findOrFail($this->editingId)->update($data);
            $message = 'Taaktemplate bijgewerkt.';
        } else {
            $data['sort_order'] = $maxOrder + 1;
            AutoTaskTemplate::create($data);
            $message = 'Taaktemplate aangemaakt.';
        }

        $this->closeModal();
        $this->dispatch('notify', message: $message);
    }

    public function toggleActive(int $id): void
    {
        $t = AutoTaskTemplate::findOrFail($id);
        $t->update(['is_active' => !$t->is_active]);
    }

    public function moveUp(int $id): void
    {
        $current = AutoTaskTemplate::findOrFail($id);
        $above   = AutoTaskTemplate::where('sort_order', '<', $current->sort_order)
            ->orderByDesc('sort_order')
            ->first();

        if ($above) {
            [$current->sort_order, $above->sort_order] = [$above->sort_order, $current->sort_order];
            $current->save();
            $above->save();
        }
    }

    public function moveDown(int $id): void
    {
        $current = AutoTaskTemplate::findOrFail($id);
        $below   = AutoTaskTemplate::where('sort_order', '>', $current->sort_order)
            ->orderBy('sort_order')
            ->first();

        if ($below) {
            [$current->sort_order, $below->sort_order] = [$below->sort_order, $current->sort_order];
            $current->save();
            $below->save();
        }
    }

    public function prepareConfirmDelete(int $id, string $name): void
    {
        $this->confirmingId   = $id;
        $this->confirmingName = $name;
        $this->dispatch('open-modal', 'confirm-template');
    }

    public function delete(int $id): void
    {
        AutoTaskTemplate::findOrFail($id)->delete();
        $this->dispatch('close-modal', 'confirm-template');
        $this->dispatch('notify', message: 'Taaktemplate verwijderd.');
    }

    public function render()
    {
        return view('livewire.admin.settings.auto-task-templates', [
            'templates'     => AutoTaskTemplate::with('assignedUser')->orderBy('sort_order')->get(),
            'users'         => User::where('is_active', true)->orderBy('name')->get(['id', 'name']),
            'triggerLabels' => self::TRIGGER_LABELS,
            'triggerColors' => self::TRIGGER_COLORS,
            'vars'          => self::VARS,
        ]);
    }

    private function resetForm(): void
    {
        $this->editingId            = null;
        $this->name                 = '';
        $this->trigger_status       = 'ondertekend';
        $this->title_template       = '';
        $this->description_template = '';
        $this->assign_to_user_id    = '';
        $this->due_days             = '';
        $this->is_active            = true;
        $this->resetValidation();
    }
}
