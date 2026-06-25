<?php

namespace App\Livewire\Admin\Settings;

use App\Models\Setting;
use Livewire\Component;

class Index extends Component
{
    public string $company_name = '';
    public string $company_address = '';
    public string $company_kvk = '';
    public string $company_representative = '';
    public string $vat_percentage = '21';
    public string $quote_validity_days = '30';

    public function mount(): void
    {
        $this->company_name           = Setting::get('company_name', 'Proud Innovations B.V.');
        $this->company_address        = Setting::get('company_address', 'Zoetermeer');
        $this->company_kvk            = Setting::get('company_kvk', '');
        $this->company_representative = Setting::get('company_representative', '');
        $this->vat_percentage         = Setting::get('vat_percentage', '21');
        $this->quote_validity_days    = Setting::get('quote_validity_days', '30');
    }

    public function save(): void
    {
        $this->validate([
            'company_name'           => 'required|string|max:255',
            'company_address'        => 'required|string|max:255',
            'company_kvk'            => 'required|string|max:50',
            'company_representative' => 'required|string|max:255',
            'vat_percentage'         => 'required|numeric|min:0|max:100',
            'quote_validity_days'    => 'required|integer|min:1',
        ], [
            'company_name.required'           => 'Bedrijfsnaam is verplicht.',
            'company_address.required'        => 'Vestigingsadres is verplicht.',
            'company_kvk.required'            => 'KvK-nummer is verplicht.',
            'company_representative.required' => 'Vertegenwoordiger is verplicht.',
            'vat_percentage.required'         => 'BTW-percentage is verplicht.',
            'quote_validity_days.required'    => 'Geldigheidsduur is verplicht.',
        ]);

        Setting::set('company_name', $this->company_name);
        Setting::set('company_address', $this->company_address);
        Setting::set('company_kvk', $this->company_kvk);
        Setting::set('company_representative', $this->company_representative);
        Setting::set('vat_percentage', $this->vat_percentage);
        Setting::set('quote_validity_days', $this->quote_validity_days);

        session()->flash('success', 'Instellingen opgeslagen.');
    }

    public function render()
    {
        return view('livewire.admin.settings.index')
            ->layout('layouts.app-admin', ['title' => 'Instellingen']);
    }
}
