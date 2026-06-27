<?php

namespace App\Livewire\Verkoper\Customers;

use App\Models\Customer;
use App\Models\CustomerNote;
use App\Models\Task;
use Livewire\Component;

class Show extends Component
{
    public Customer $customer;

    public string $newNote = '';

    public bool $editingInfo = false;
    public string $companyName = '';
    public string $address = '';
    public string $kvkNumber = '';
    public string $contactName = '';
    public string $contactEmail = '';
    public string $contactPhone = '';
    public string $website = '';

    public bool $showTaskForm = false;
    public string $taskTitle = '';
    public string $taskDescription = '';
    public string $taskDueDate = '';

    public function mount(Customer $customer): void
    {
        $this->customer = $customer->load(['quotes.items.product', 'notes.user', 'tasks.assignedTo']);
        $this->fillEditForm();
    }

    private function fillEditForm(): void
    {
        $this->companyName  = $this->customer->company_name;
        $this->address      = $this->customer->address;
        $this->kvkNumber    = $this->customer->kvk_number;
        $this->contactName  = $this->customer->contact_name;
        $this->contactEmail = $this->customer->contact_email;
        $this->contactPhone = $this->customer->contact_phone ?? '';
        $this->website      = $this->customer->website ?? '';
    }

    public function startEditing(): void
    {
        $this->editingInfo = true;
    }

    public function cancelEditing(): void
    {
        $this->editingInfo = false;
        $this->fillEditForm();
    }

    public function saveInfo(): void
    {
        $this->validate([
            'companyName'  => 'required|string|max:255',
            'address'      => 'required|string|max:500',
            'kvkNumber'    => 'required|string|max:50',
            'contactName'  => 'required|string|max:255',
            'contactEmail' => 'required|email|max:255',
            'contactPhone' => 'nullable|string|max:50',
            'website'      => 'nullable|url|max:255',
        ], [
            'companyName.required' => 'Bedrijfsnaam is verplicht.',
            'contactEmail.email'   => 'Voer een geldig e-mailadres in.',
            'website.url'          => 'Voer een geldig websiteadres in (inclusief https://).',
        ]);

        $this->customer->update([
            'company_name'  => $this->companyName,
            'address'       => $this->address,
            'kvk_number'    => $this->kvkNumber,
            'contact_name'  => $this->contactName,
            'contact_email' => $this->contactEmail,
            'contact_phone' => $this->contactPhone ?: null,
            'website'       => $this->website ?: null,
        ]);

        $this->editingInfo = false;
        $this->customer->refresh();
        session()->flash('success', 'Klantgegevens bijgewerkt.');
    }

    public function addNote(): void
    {
        $this->validate(['newNote' => 'required|string|max:2000'], [
            'newNote.required' => 'Notitie mag niet leeg zijn.',
        ]);

        CustomerNote::create([
            'customer_id' => $this->customer->id,
            'user_id'     => auth()->id(),
            'body'        => $this->newNote,
        ]);

        $this->newNote = '';
        $this->customer->load('notes.user');
        session()->flash('success', 'Notitie toegevoegd.');
    }

    public function deleteNote(int $noteId): void
    {
        $note = CustomerNote::findOrFail($noteId);
        if ($note->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            return;
        }
        $note->delete();
        $this->customer->load('notes.user');
    }

    public function saveTask(): void
    {
        $this->validate([
            'taskTitle'       => 'required|string|max:255',
            'taskDescription' => 'nullable|string|max:2000',
            'taskDueDate'     => 'nullable|date',
        ], [
            'taskTitle.required' => 'Taakomschrijving is verplicht.',
        ]);

        Task::create([
            'created_by_user_id'  => auth()->id(),
            'assigned_to_user_id' => auth()->id(),
            'customer_id'         => $this->customer->id,
            'title'               => $this->taskTitle,
            'description'         => $this->taskDescription ?: null,
            'status'              => 'open',
            'due_date'            => $this->taskDueDate ?: null,
        ]);

        $this->taskTitle = '';
        $this->taskDescription = '';
        $this->taskDueDate = '';
        $this->showTaskForm = false;
        $this->customer->load('tasks.assignedTo');
        session()->flash('success', 'Taak aangemaakt.');
    }

    public function render()
    {
        $layout = auth()->user()->role === 'admin' ? 'layouts.app-admin' : 'layouts.app-verkoper';

        return view('livewire.verkoper.customers.show', [
            'activeConfig' => $this->customer->activeConfiguration(),
        ])->layout($layout, ['title' => $this->customer->company_name]);
    }
}
