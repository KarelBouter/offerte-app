<?php

namespace Database\Seeders;

use App\Models\Onderhoudsgroep;
use App\Models\Product;
use Illuminate\Database\Seeder;

class OnderhoudsgroepSeeder extends Seeder
{
    public function run(): void
    {
        // Camera-groep
        $cameraBasis   = Product::where('name', 'Onderhoudscontract camera — basis')->first();
        $cameraPerStuk = Product::where('name', 'Onderhoudscontract camera — per stuk')->first();
        $cameraGroep   = Onderhoudsgroep::firstOrCreate(
            ['naam' => 'Beveiligingscamera\'s'],
            [
                'basisproduct_id'    => $cameraBasis?->id,
                'per_stuk_product_id'=> $cameraPerStuk?->id,
                'is_actief'          => true,
            ]
        );
        // Link camera product to this group
        Product::where('name', 'Beveiligingscamera (Unifi Protect)')
            ->update(['onderhoudsgroep_id' => $cameraGroep->id]);

        // Access point-groep
        $apBasis   = Product::where('name', 'Onderhoudscontract access point — basis')->first();
        $apPerStuk = Product::where('name', 'Onderhoudscontract access point — per stuk')->first();
        $apGroep   = Onderhoudsgroep::firstOrCreate(
            ['naam' => 'Access points'],
            [
                'basisproduct_id'    => $apBasis?->id,
                'per_stuk_product_id'=> $apPerStuk?->id,
                'is_actief'          => true,
            ]
        );
        // Link AP product to this group
        Product::where('name', 'Unifi Access Point')
            ->update(['onderhoudsgroep_id' => $apGroep->id]);
    }
}
