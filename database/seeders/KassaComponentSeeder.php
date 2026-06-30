<?php

namespace Database\Seeders;

use App\Models\KassaComponent;
use Illuminate\Database\Seeder;

class KassaComponentSeeder extends Seeder
{
    public function run(): void
    {
        $componenten = [
            ['naam' => 'Kassa',       'poorten_per_kassa' => 1, 'poe_required' => false, 'sort_order' => 1],
            ['naam' => 'Pinautomaat', 'poorten_per_kassa' => 1, 'poe_required' => false, 'sort_order' => 2],
        ];

        foreach ($componenten as $data) {
            KassaComponent::firstOrCreate(['naam' => $data['naam']], array_merge($data, [
                'is_actief' => true,
            ]));
        }
    }
}
