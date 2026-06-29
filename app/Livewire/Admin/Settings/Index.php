<?php

namespace App\Livewire\Admin\Settings;

use App\Models\Setting;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class Index extends Component
{
    use WithFileUploads;

    public string $company_name            = '';
    public string $company_address         = '';
    public string $company_kvk             = '';
    public string $company_representative  = '';
    public string $company_email           = '';
    public string $company_phone           = '';
    public string $vat_percentage          = '21';
    public string $quote_validity_days     = '30';
    public string $default_quote_note      = '';
    public $logo = null;
    public ?string $currentLogoPath        = null;
    public $company_signature              = null;
    public ?string $currentSignaturePath   = null;
    public bool   $require_signature              = true;
    public string $payment_onetime_mode           = '100_vooraf';
    public string $payment_service_days           = '14';
    public bool   $payment_service_yearly_advance = true;

    public function mount(): void
    {
        $this->company_name           = Setting::get('company_name', 'Proud Innovations B.V.');
        $this->company_address        = Setting::get('company_address', 'Zoetermeer');
        $this->company_kvk            = Setting::get('company_kvk', '');
        $this->company_representative = Setting::get('company_representative', '');
        $this->company_email          = Setting::get('company_email', '');
        $this->company_phone          = Setting::get('company_phone', '');
        $this->vat_percentage         = Setting::get('vat_percentage', '21');
        $this->quote_validity_days    = Setting::get('quote_validity_days', '30');
        $this->default_quote_note     = Setting::get('default_quote_note', '');
        $this->currentLogoPath        = Setting::get('logo_path');
        $this->currentSignaturePath           = Setting::get('company_signature_path');
        $this->require_signature              = (bool) Setting::get('require_signature', '1');
        $this->payment_onetime_mode           = Setting::get('payment_onetime_mode', '100_vooraf');
        $this->payment_service_days           = Setting::get('payment_service_days', '14');
        $this->payment_service_yearly_advance = (bool) Setting::get('payment_service_yearly_advance', '1');
    }

    public function save(): void
    {
        $this->validate([
            'company_name'           => 'required|string|max:255',
            'company_address'        => 'required|string|max:255',
            'company_kvk'            => 'required|string|max:50',
            'company_representative' => 'required|string|max:255',
            'company_email'          => 'nullable|email|max:255',
            'company_phone'          => 'nullable|string|max:50',
            'vat_percentage'         => 'required|numeric|min:0|max:100',
            'quote_validity_days'    => 'required|integer|min:1',
            'default_quote_note'     => 'nullable|string|max:1000',
            'logo'                   => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'company_signature'      => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'payment_onetime_mode'   => 'required|in:100_vooraf,50_50',
            'payment_service_days'   => 'required|integer|min:1|max:90',
        ], [
            'company_name.required'           => 'Bedrijfsnaam is verplicht.',
            'company_address.required'        => 'Vestigingsadres is verplicht.',
            'company_kvk.required'            => 'KvK-nummer is verplicht.',
            'company_representative.required' => 'Vertegenwoordiger is verplicht.',
            'company_email.email'             => 'Voer een geldig e-mailadres in.',
            'vat_percentage.required'         => 'BTW-percentage is verplicht.',
            'quote_validity_days.required'    => 'Geldigheidsduur is verplicht.',
            'logo.image'                      => 'Het logo moet een afbeelding zijn.',
            'logo.mimes'                      => 'Alleen JPG en PNG zijn toegestaan.',
            'logo.max'                        => 'Logo mag maximaal 2 MB zijn.',
        ]);

        if ($this->logo) {
            if ($this->currentLogoPath) {
                Storage::disk('public')->delete($this->currentLogoPath);
            }
            $path = $this->logo->store('logo', 'public');
            Setting::set('logo_path', $path);
            $this->currentLogoPath = $path;
            $this->logo = null;
        }

        if ($this->company_signature) {
            if ($this->currentSignaturePath && Storage::disk('public')->exists($this->currentSignaturePath)) {
                Storage::disk('public')->delete($this->currentSignaturePath);
            }
            $path = $this->company_signature->store('signatures', 'public');
            Setting::set('company_signature_path', $path);
            $this->currentSignaturePath = $path;
            $this->company_signature = null;
        }

        Setting::set('company_name', $this->company_name);
        Setting::set('company_address', $this->company_address);
        Setting::set('company_kvk', $this->company_kvk);
        Setting::set('company_representative', $this->company_representative);
        Setting::set('company_email', $this->company_email);
        Setting::set('company_phone', $this->company_phone);
        Setting::set('vat_percentage', $this->vat_percentage);
        Setting::set('quote_validity_days', $this->quote_validity_days);
        Setting::set('default_quote_note', $this->default_quote_note);
        Setting::set('require_signature',              $this->require_signature ? '1' : '0');
        Setting::set('payment_onetime_mode',           $this->payment_onetime_mode);
        Setting::set('payment_service_days',           $this->payment_service_days);
        Setting::set('payment_service_yearly_advance', $this->payment_service_yearly_advance ? '1' : '0');

        session()->flash('success', 'Instellingen opgeslagen.');
    }

    public function render()
    {
        return view('livewire.admin.settings.index')
            ->layout('layouts.app-admin', ['title' => 'Instellingen']);
    }
}
