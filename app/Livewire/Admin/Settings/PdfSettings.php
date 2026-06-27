<?php

namespace App\Livewire\Admin\Settings;

use App\Models\Setting;
use App\Support\PdfDefaults;
use Livewire\Component;

class PdfSettings extends Component
{
    public string $pdf_primary_color     = PdfDefaults::PRIMARY_COLOR;
    public string $pdf_font_family       = PdfDefaults::FONT_FAMILY;
    public string $pdf_font_size_body    = PdfDefaults::FONT_SIZE_BODY;
    public string $pdf_font_size_heading = PdfDefaults::FONT_SIZE_HEADING;
    public string $pdf_margin_mm         = PdfDefaults::MARGIN_MM;
    public string $pdf_footer_text       = PdfDefaults::FOOTER_TEXT;

    public string $pdf_tekst_artikel_2           = PdfDefaults::ARTIKEL_2;
    public string $pdf_tekst_artikel_2_afbakening = PdfDefaults::ARTIKEL_2_AFBAKENING;
    public string $pdf_tekst_artikel_3           = PdfDefaults::ARTIKEL_3;
    public string $pdf_tekst_artikel_3_2         = PdfDefaults::ARTIKEL_3_2;
    public string $pdf_tekst_artikel_6_afbakening = PdfDefaults::ARTIKEL_6_AFBAKENING;
    public string $pdf_tekst_artikel_6_2         = PdfDefaults::ARTIKEL_6_2;
    public string $pdf_tekst_artikel_7           = PdfDefaults::ARTIKEL_7;
    public string $pdf_tekst_artikel_8           = PdfDefaults::ARTIKEL_8;
    public string $pdf_tekst_artikel_8_2         = PdfDefaults::ARTIKEL_8_2;
    public string $pdf_tekst_artikel_9_1         = PdfDefaults::ARTIKEL_9_1;
    public string $pdf_tekst_artikel_9_2         = PdfDefaults::ARTIKEL_9_2;
    public string $pdf_tekst_artikel_9_3         = PdfDefaults::ARTIKEL_9_3;
    public string $pdf_tekst_artikel_9_4         = PdfDefaults::ARTIKEL_9_4;
    public string $pdf_tekst_artikel_9_5         = PdfDefaults::ARTIKEL_9_5;
    public string $pdf_tekst_artikel_9_6         = PdfDefaults::ARTIKEL_9_6;
    public string $pdf_tekst_artikel_9_7         = PdfDefaults::ARTIKEL_9_7;
    public string $pdf_tekst_artikel_10_footer   = PdfDefaults::ARTIKEL_10_FOOTER;

    public function mount(): void
    {
        $defaults = $this->defaultMap();
        foreach ($this->settingsKeys() as $key) {
            $this->$key = Setting::get($key, $defaults[$key] ?? $this->$key);
        }
    }

    public function save(): void
    {
        $this->validate($this->rules());

        foreach ($this->settingsKeys() as $key) {
            Setting::set($key, $this->$key);
        }

        session()->flash('pdf_success', 'PDF-instellingen opgeslagen.');
    }

    public function resetField(string $field): void
    {
        $constMap = $this->defaultMap();
        if (isset($constMap[$field])) {
            $this->$field = $constMap[$field];
        }
    }

    public function render()
    {
        return view('livewire.admin.settings.pdf-settings');
    }

    private function rules(): array
    {
        return [
            'pdf_primary_color'     => 'required|string|max:20',
            'pdf_font_family'       => 'required|string|max:100',
            'pdf_font_size_body'    => 'required|numeric|min:6|max:20',
            'pdf_font_size_heading' => 'required|numeric|min:6|max:24',
            'pdf_margin_mm'         => 'required|numeric|min:5|max:50',
            'pdf_footer_text'       => 'nullable|string|max:255',
            'pdf_tekst_artikel_2'             => 'nullable|string|max:5000',
            'pdf_tekst_artikel_2_afbakening'  => 'nullable|string|max:5000',
            'pdf_tekst_artikel_3'             => 'nullable|string|max:5000',
            'pdf_tekst_artikel_3_2'           => 'nullable|string|max:5000',
            'pdf_tekst_artikel_6_afbakening'  => 'nullable|string|max:5000',
            'pdf_tekst_artikel_6_2'           => 'nullable|string|max:5000',
            'pdf_tekst_artikel_7'             => 'nullable|string|max:5000',
            'pdf_tekst_artikel_8'             => 'nullable|string|max:5000',
            'pdf_tekst_artikel_8_2'           => 'nullable|string|max:5000',
            'pdf_tekst_artikel_9_1'           => 'nullable|string|max:5000',
            'pdf_tekst_artikel_9_2'           => 'nullable|string|max:5000',
            'pdf_tekst_artikel_9_3'           => 'nullable|string|max:5000',
            'pdf_tekst_artikel_9_4'           => 'nullable|string|max:5000',
            'pdf_tekst_artikel_9_5'           => 'nullable|string|max:5000',
            'pdf_tekst_artikel_9_6'           => 'nullable|string|max:5000',
            'pdf_tekst_artikel_9_7'           => 'nullable|string|max:5000',
            'pdf_tekst_artikel_10_footer'     => 'nullable|string|max:5000',
        ];
    }

    private function settingsKeys(): array
    {
        return [
            'pdf_primary_color',
            'pdf_font_family',
            'pdf_font_size_body',
            'pdf_font_size_heading',
            'pdf_margin_mm',
            'pdf_footer_text',
            'pdf_tekst_artikel_2',
            'pdf_tekst_artikel_2_afbakening',
            'pdf_tekst_artikel_3',
            'pdf_tekst_artikel_3_2',
            'pdf_tekst_artikel_6_afbakening',
            'pdf_tekst_artikel_6_2',
            'pdf_tekst_artikel_7',
            'pdf_tekst_artikel_8',
            'pdf_tekst_artikel_8_2',
            'pdf_tekst_artikel_9_1',
            'pdf_tekst_artikel_9_2',
            'pdf_tekst_artikel_9_3',
            'pdf_tekst_artikel_9_4',
            'pdf_tekst_artikel_9_5',
            'pdf_tekst_artikel_9_6',
            'pdf_tekst_artikel_9_7',
            'pdf_tekst_artikel_10_footer',
        ];
    }

    private function defaultMap(): array
    {
        return [
            'pdf_primary_color'              => PdfDefaults::PRIMARY_COLOR,
            'pdf_font_family'                => PdfDefaults::FONT_FAMILY,
            'pdf_font_size_body'             => PdfDefaults::FONT_SIZE_BODY,
            'pdf_font_size_heading'          => PdfDefaults::FONT_SIZE_HEADING,
            'pdf_margin_mm'                  => PdfDefaults::MARGIN_MM,
            'pdf_footer_text'                => PdfDefaults::FOOTER_TEXT,
            'pdf_tekst_artikel_2'            => PdfDefaults::ARTIKEL_2,
            'pdf_tekst_artikel_2_afbakening' => PdfDefaults::ARTIKEL_2_AFBAKENING,
            'pdf_tekst_artikel_3'            => PdfDefaults::ARTIKEL_3,
            'pdf_tekst_artikel_3_2'          => PdfDefaults::ARTIKEL_3_2,
            'pdf_tekst_artikel_6_afbakening' => PdfDefaults::ARTIKEL_6_AFBAKENING,
            'pdf_tekst_artikel_6_2'          => PdfDefaults::ARTIKEL_6_2,
            'pdf_tekst_artikel_7'            => PdfDefaults::ARTIKEL_7,
            'pdf_tekst_artikel_8'            => PdfDefaults::ARTIKEL_8,
            'pdf_tekst_artikel_8_2'          => PdfDefaults::ARTIKEL_8_2,
            'pdf_tekst_artikel_9_1'          => PdfDefaults::ARTIKEL_9_1,
            'pdf_tekst_artikel_9_2'          => PdfDefaults::ARTIKEL_9_2,
            'pdf_tekst_artikel_9_3'          => PdfDefaults::ARTIKEL_9_3,
            'pdf_tekst_artikel_9_4'          => PdfDefaults::ARTIKEL_9_4,
            'pdf_tekst_artikel_9_5'          => PdfDefaults::ARTIKEL_9_5,
            'pdf_tekst_artikel_9_6'          => PdfDefaults::ARTIKEL_9_6,
            'pdf_tekst_artikel_9_7'          => PdfDefaults::ARTIKEL_9_7,
            'pdf_tekst_artikel_10_footer'    => PdfDefaults::ARTIKEL_10_FOOTER,
        ];
    }
}
