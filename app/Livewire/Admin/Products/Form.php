<?php

namespace App\Livewire\Admin\Products;

use App\Enums\ProductCategorie;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithFileUploads;

class Form extends Component
{
    use WithFileUploads;

    public ?Product $product = null;

    public string $name = '';
    public string $sku = '';
    public string $category = 'Hardware';
    public string $description = '';
    public float $unit_price = 0.0;
    public string $unit = 'stuk';
    public ?int $min_quantity = null;
    public ?int $max_quantity = null;
    public $image = null;
    public ?string $existingImagePath = null;
    public int $sort_order = 0;
    public bool $is_active = true;
    public bool $is_price_on_quote = false;
    public ?int $poe_wattage_output = null;
    public ?int $poe_wattage_input = null;
    public ?float $price_per_meter = null;
    public ?int $switch_ports_total = null;
    public ?int $switch_ports_poe = null;
    public ?int $poorten_benodigd = null;
    public string $installatie_instructie  = '';
    public string $werkbon_zichtbaarheid    = 'automatisch';
    public bool   $vereist_servicecontract  = false;
    public bool   $verberg_in_configurator  = false;
    public bool   $is_hardware_basisoptie   = false;
    public bool   $is_ups                   = false;

    public function mount(?Product $product = null): void
    {
        if ($product?->exists) {
            $this->product = $product;
            $this->name = $product->name;
            $this->sku = $product->sku ?? '';
            $this->category = $product->category;
            $this->description = $product->description;
            $this->unit_price = (float) $product->unit_price;
            $this->unit = $product->unit;
            $this->min_quantity = $product->min_quantity;
            $this->max_quantity = $product->max_quantity;
            $this->existingImagePath = $product->image_path;
            $this->sort_order = $product->sort_order;
            $this->is_active = (bool) $product->is_active;
            $this->is_price_on_quote = (bool) $product->is_price_on_quote;
            $this->poe_wattage_output = $product->poe_wattage_output;
            $this->poe_wattage_input  = $product->poe_wattage_input;
            $this->price_per_meter    = $product->price_per_meter ? (float) $product->price_per_meter : null;
            $this->switch_ports_total = $product->switch_ports_total;
            $this->switch_ports_poe   = $product->switch_ports_poe;
            $this->poorten_benodigd       = $product->poorten_benodigd;
            $this->installatie_instructie  = $product->installatie_instructie ?? '';
            $this->werkbon_zichtbaarheid   = $product->werkbon_zichtbaarheid ?? 'automatisch';
            $this->vereist_servicecontract = (bool) $product->vereist_servicecontract;
            $this->verberg_in_configurator = (bool) $product->verberg_in_configurator;
            $this->is_hardware_basisoptie  = (bool) $product->is_hardware_basisoptie;
            $this->is_ups                  = (bool) $product->is_ups;
        }
    }

    public function save(): void
    {
        $this->validate(
            [
                'name' => 'required|string|max:255',
                'sku' => 'nullable|string|max:100',
                'category' => 'required|in:'.implode(',', ProductCategorie::values()),
                'description' => 'required|string',
                'unit_price' => $this->is_price_on_quote ? 'numeric|min:0' : 'required|numeric|min:0',
                'unit' => 'required|in:stuk,dag,jaar,set',
                'min_quantity' => 'nullable|integer|min:1',
                'max_quantity' => 'nullable|integer|min:1|gte:min_quantity',
                'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
                'sort_order' => 'required|integer|min:0',
                'poe_wattage_output'  => 'nullable|integer|min:0',
                'poe_wattage_input'   => 'nullable|integer|min:0',
                'price_per_meter'     => 'nullable|numeric|min:0',
                'switch_ports_total'  => 'nullable|integer|min:1',
                'switch_ports_poe'    => 'nullable|integer|min:0|lte:switch_ports_total',
                'poorten_benodigd'        => 'nullable|integer|min:0',
                'installatie_instructie'  => 'nullable|string',
                'werkbon_zichtbaarheid'    => 'required|in:automatisch,altijd,verbergen',
                'vereist_servicecontract'  => 'boolean',
                'verberg_in_configurator'  => 'boolean',
                'is_hardware_basisoptie'   => 'boolean',
                'is_ups'                   => 'boolean',
            ],
            [
                'name.required' => 'Naam is verplicht.',
                'name.max' => 'Naam mag maximaal 255 tekens bevatten.',
                'category.required' => 'Categorie is verplicht.',
                'category.in' => 'Kies een geldige categorie.',
                'description.required' => 'Omschrijving is verplicht.',
                'unit_price.required' => 'Prijs is verplicht als het product geen prijs op offerte heeft.',
                'unit_price.numeric' => 'Prijs moet een getal zijn.',
                'unit_price.min' => 'Prijs moet 0 of hoger zijn.',
                'unit.required' => 'Eenheid is verplicht.',
                'unit.in' => 'Kies een geldige eenheid.',
                'min_quantity.integer' => 'Minimale afname moet een geheel getal zijn.',
                'min_quantity.min' => 'Minimale afname moet minimaal 1 zijn.',
                'max_quantity.integer' => 'Maximale afname moet een geheel getal zijn.',
                'max_quantity.min' => 'Maximale afname moet minimaal 1 zijn.',
                'max_quantity.gte' => 'Maximale afname moet gelijk aan of groter zijn dan de minimale afname.',
                'image.image' => 'Het bestand moet een afbeelding zijn.',
                'image.mimes' => 'Alleen JPG en PNG zijn toegestaan.',
                'image.max' => 'De afbeelding mag maximaal 2 MB zijn.',
                'sort_order.required' => 'Volgorde is verplicht.',
                'sort_order.integer' => 'Volgorde moet een geheel getal zijn.',
                'sort_order.min' => 'Volgorde moet 0 of hoger zijn.',
            ]
        );

        $data = [
            'name' => $this->name,
            'sku' => $this->sku ?: null,
            'category' => $this->category,
            'description' => $this->description,
            'unit_price' => $this->is_price_on_quote ? 0 : $this->unit_price,
            'unit' => $this->unit,
            'min_quantity' => $this->min_quantity,
            'max_quantity' => $this->max_quantity,
            'sort_order'         => $this->sort_order,
            'is_active'          => $this->is_active,
            'is_price_on_quote'  => $this->is_price_on_quote,
            'poe_wattage_output'  => $this->poe_wattage_output,
            'poe_wattage_input'   => $this->poe_wattage_input,
            'price_per_meter'     => $this->price_per_meter,
            'switch_ports_total'  => $this->switch_ports_total,
            'switch_ports_poe'    => $this->switch_ports_poe,
            'poorten_benodigd'        => $this->poorten_benodigd,
            'installatie_instructie'  => $this->installatie_instructie ?: null,
            'werkbon_zichtbaarheid'   => $this->werkbon_zichtbaarheid,
            'vereist_servicecontract' => $this->vereist_servicecontract,
            'verberg_in_configurator' => $this->verberg_in_configurator,
            'is_hardware_basisoptie'  => $this->is_hardware_basisoptie,
            'is_ups'                  => $this->is_ups,
        ];

        if ($this->image) {
            $data['image_path'] = $this->image->store('products', 'public');
        }

        if ($this->product?->exists) {
            $this->product->update($data);
        } else {
            Product::create($data);
        }

        session()->flash('success', 'Product succesvol opgeslagen.');

        $this->redirect(route('beheer.producten.index'));
    }

    public function render()
    {
        $title = $this->product?->exists ? 'Product bewerken' : 'Nieuw product';

        return view('livewire.admin.products.form')
            ->layout('layouts.app-admin', ['title' => $title]);
    }
}
