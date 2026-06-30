<?php

namespace App\Livewire\Admin\KassaComponenten;

use App\Models\KassaComponent;
use Livewire\Component;

class Index extends Component
{
    public bool $showModal = false;
    public ?int $editingId = null;

    public ?int $confirmingId     = null;
    public string $confirmingName = '';

    // Form fields
    public string $naam             = '';
    public int    $poorten_per_kassa = 1;
    public bool   $poe_required     = false;
    public bool   $is_actief        = true;
    public int    $sort_order       = 0;

    public function openCreate(): void
    {
        $this->resetForm();
        $this->editingId = null;
        $this->showModal = true;
    }

    public function openEdit(int $id): void
    {
        $component = KassaComponent::findOrFail($id);
        $this->editingId          = $id;
        $this->naam               = $component->naam;
        $this->poorten_per_kassa  = $component->poorten_per_kassa;
        $this->poe_required       = $component->poe_required;
        $this->is_actief          = $component->is_actief;
        $this->sort_order         = $component->sort_order;
        $this->showModal          = true;
    }

    public function save(): void
    {
        $this->validate([
            'naam'              => 'required|string|max:100',
            'poorten_per_kassa' => 'required|integer|min:0',
            'sort_order'        => 'required|integer|min:0',
        ], [
            'naam.required'              => 'Naam is verplicht.',
            'poorten_per_kassa.required' => 'Poorten per kassa is verplicht.',
            'poorten_per_kassa.integer'  => 'Poorten per kassa moet een geheel getal zijn.',
            'poorten_per_kassa.min'      => 'Poorten per kassa moet 0 of meer zijn.',
        ]);

        $data = [
            'naam'              => $this->naam,
            'poorten_per_kassa' => $this->poorten_per_kassa,
            'poe_required'      => $this->poe_required,
            'is_actief'         => $this->is_actief,
            'sort_order'        => $this->sort_order,
        ];

        if ($this->editingId) {
            KassaComponent::findOrFail($this->editingId)->update($data);
            session()->flash('success', 'Component bijgewerkt.');
        } else {
            KassaComponent::create($data);
            session()->flash('success', 'Component toegevoegd.');
        }

        $this->showModal = false;
        $this->resetForm();
    }

    public function prepareConfirmDelete(int $id, string $naam): void
    {
        $this->confirmingId   = $id;
        $this->confirmingName = $naam;
        $this->dispatch('open-modal', 'confirm-kassa-component');
    }

    public function delete(int $id): void
    {
        KassaComponent::findOrFail($id)->delete();
        $this->dispatch('close-modal', 'confirm-kassa-component');
        session()->flash('success', 'Component verwijderd.');
    }

    public function toggleActief(int $id): void
    {
        $component = KassaComponent::findOrFail($id);
        $component->update(['is_actief' => !$component->is_actief]);
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->naam              = '';
        $this->poorten_per_kassa = 1;
        $this->poe_required      = false;
        $this->is_actief         = true;
        $this->sort_order        = 0;
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.admin.kassa-componenten.index', [
            'componenten' => KassaComponent::orderBy('sort_order')->orderBy('naam')->get(),
        ])->layout('layouts.app-admin', ['title' => 'Kassa-componenten']);
    }
}
