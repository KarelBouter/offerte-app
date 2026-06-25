<?php

namespace App\Livewire\Admin\Products;

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
        }
    }

    public function save(): void
    {
        $this->validate(
            [
                'name' => 'required|string|max:255',
                'sku' => 'nullable|string|max:100',
                'category' => 'required|in:Hardware,Netwerk,Beveiliging,Installatie,Service',
                'description' => 'required|string',
                'unit_price' => $this->is_price_on_quote ? 'numeric|min:0' : 'required|numeric|min:0',
                'unit' => 'required|in:stuk,dag,jaar,set',
                'min_quantity' => 'nullable|integer|min:1',
                'max_quantity' => 'nullable|integer|min:1|gte:min_quantity',
                'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
                'sort_order' => 'required|integer|min:0',
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
            'sort_order' => $this->sort_order,
            'is_active' => $this->is_active,
            'is_price_on_quote' => $this->is_price_on_quote,
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

        $this->redirect(route('admin.products.index'));
    }

    public function render()
    {
        $title = $this->product?->exists ? 'Product bewerken' : 'Nieuw product';

        return view('livewire.admin.products.form')
            ->layout('layouts.app-admin', ['title' => $title]);
    }
}
