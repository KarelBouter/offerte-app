<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            // Hardware
            [
                'name' => 'Optie A — Tower server (RAID 1)',
                'category' => 'Hardware',
                'description' => 'Tower server met RAID 1 configuratie voor betrouwbare lokale dataopslag.',
                'unit_price' => 3500.00,
                'unit' => 'set',
                'is_price_on_quote' => false,
                'sort_order' => 10,
                'is_hardware_basisoptie' => true,
                'vereist_servicecontract' => true,
            ],
            [
                'name' => 'Optie B — HA Cluster (3 nodes)',
                'category' => 'Hardware',
                'description' => 'High Availability cluster bestaande uit 3 nodes voor maximale uptime.',
                'unit_price' => 9500.00,
                'unit' => 'set',
                'is_price_on_quote' => false,
                'sort_order' => 20,
                'is_hardware_basisoptie' => true,
                'vereist_servicecontract' => true,
            ],
            [
                'name' => 'NUC node',
                'category' => 'Hardware',
                'description' => 'Compacte NUC node voor cluster-uitbreiding.',
                'unit_price' => 0.00,
                'unit' => 'stuk',
                'is_price_on_quote' => true,
                'sort_order' => 30,
                'verberg_in_configurator' => true,
            ],
            [
                'name' => '2.5" SSD',
                'category' => 'Hardware',
                'description' => '2.5 inch SSD voor uitbreiding of vervanging van opslagcapaciteit.',
                'unit_price' => 0.00,
                'unit' => 'stuk',
                'is_price_on_quote' => true,
                'sort_order' => 40,
                'verberg_in_configurator' => true,
            ],
            [
                'name' => 'Firewall',
                'category' => 'Hardware',
                'description' => 'Firewall — inbegrepen bij Optie A en B.',
                'unit_price' => 0.00,
                'unit' => 'stuk',
                'is_price_on_quote' => true,
                'sort_order' => 50,
                'verberg_in_configurator' => true,
            ],
            [
                'name' => 'Switch standaard',
                'category' => 'Hardware',
                'description' => 'Standaard netwerkswitch.',
                'unit_price' => 0.00,
                'unit' => 'stuk',
                'is_price_on_quote' => true,
                'sort_order' => 60,
                'verberg_in_configurator' => true,
            ],
            [
                'name' => 'PoE Switch 8-poorts',
                'category' => 'Hardware',
                'description' => 'Power over Ethernet switch met 8 poorten.',
                'unit_price' => 0.00,
                'unit' => 'stuk',
                'is_price_on_quote' => true,
                'sort_order' => 70,
                'verberg_in_configurator' => true,
            ],
            [
                'name' => 'PoE Switch 16-poorts',
                'category' => 'Hardware',
                'description' => 'Power over Ethernet switch met 16 poorten.',
                'unit_price' => 0.00,
                'unit' => 'stuk',
                'is_price_on_quote' => true,
                'sort_order' => 80,
                'verberg_in_configurator' => true,
            ],
            [
                'name' => 'UPS',
                'category' => 'Hardware',
                'description' => 'Uninterruptible Power Supply voor noodstroom bij stroomuitval.',
                'unit_price' => 860.00,
                'unit' => 'stuk',
                'is_price_on_quote' => false,
                'sort_order' => 90,
                'is_ups' => true,
            ],

            // Netwerk
            [
                'name' => 'Unifi Access Point',
                'category' => 'Netwerk',
                'description' => 'Unifi draadloos access point voor betrouwbaar wifi-netwerk.',
                'unit_price' => 0.00,
                'unit' => 'stuk',
                'is_price_on_quote' => true,
                'sort_order' => 10,
            ],

            // Beveiliging
            [
                'name' => 'Beveiligingscamera (Unifi Protect)',
                'category' => 'Beveiliging',
                'description' => 'IP-beveiligingscamera geschikt voor Unifi Protect NVR systeem.',
                'unit_price' => 0.00,
                'unit' => 'stuk',
                'is_price_on_quote' => true,
                'sort_order' => 10,
            ],
            [
                'name' => 'NVR (Network Video Recorder)',
                'category' => 'Beveiliging',
                'description' => 'Network Video Recorder voor opslag en beheer van camerabeelden.',
                'unit_price' => 0.00,
                'unit' => 'stuk',
                'is_price_on_quote' => true,
                'sort_order' => 20,
                'verberg_in_configurator' => true,
            ],

            // Installatie
            [
                'name' => 'Installatie halve dag (±4 uur)',
                'category' => 'Installatie',
                'description' => 'On-site installatie en configuratie, halve dag (circa 4 uur).',
                'unit_price' => 450.00,
                'unit' => 'dag',
                'is_price_on_quote' => false,
                'sort_order' => 10,
                'verberg_in_configurator' => true,
            ],
            [
                'name' => 'Installatie hele dag (±8 uur)',
                'category' => 'Installatie',
                'description' => 'On-site installatie en configuratie, hele dag (circa 8 uur).',
                'unit_price' => 800.00,
                'unit' => 'dag',
                'is_price_on_quote' => false,
                'sort_order' => 20,
                'verberg_in_configurator' => true,
            ],
            [
                'name' => 'Installatie extra dagdeel',
                'category' => 'Installatie',
                'description' => 'Extra dagdeel on-site voor aanvullende installatie of configuratie.',
                'unit_price' => 450.00,
                'unit' => 'dag',
                'is_price_on_quote' => false,
                'sort_order' => 30,
            ],
            [
                'name' => 'Installatiemateriaal',
                'category' => 'Installatie',
                'description' => 'Bekabeling, bevestigingsmateriaal en overig installatiemateriaal.',
                'unit_price' => 0.00,
                'unit' => 'set',
                'is_price_on_quote' => true,
                'sort_order' => 40,
            ],

            // Service
            [
                'name' => 'Servicecontract Standaard',
                'category' => 'Service',
                'description' => 'Jaarlijks servicecontract standaard — inclusief monitoring, updates en helpdesk.',
                'unit_price' => 8799.00,
                'unit' => 'jaar',
                'is_price_on_quote' => false,
                'sort_order' => 10,
            ],
            [
                'name' => 'Servicecontract Premium',
                'category' => 'Service',
                'description' => 'Jaarlijks servicecontract premium — inclusief uitgebreide SLA, proactief beheer en prioriteit support.',
                'unit_price' => 13764.00,
                'unit' => 'jaar',
                'is_price_on_quote' => false,
                'sort_order' => 20,
            ],
            // Onderhoudscontracten — verborgen, worden via toggles in de configurator toegevoegd
            [
                'name'        => 'Onderhoudscontract camera — basis',
                'category'    => 'Service',
                'description' => 'Jaarlijks onderhoudscontract beveiligingscamera\'s — basistarief per installatie.',
                'unit_price'  => 250.00,
                'unit'        => 'jaar',
                'is_price_on_quote'      => false,
                'verberg_in_configurator'=> true,
                'sort_order'  => 30,
            ],
            [
                'name'        => 'Onderhoudscontract camera — per stuk',
                'category'    => 'Service',
                'description' => 'Jaarlijks onderhoudscontract beveiligingscamera\'s — toeslag per camera.',
                'unit_price'  => 35.00,
                'unit'        => 'jaar',
                'is_price_on_quote'      => false,
                'verberg_in_configurator'=> true,
                'sort_order'  => 31,
            ],
            [
                'name'        => 'Onderhoudscontract access point — basis',
                'category'    => 'Service',
                'description' => 'Jaarlijks onderhoudscontract access points — basistarief per installatie.',
                'unit_price'  => 150.00,
                'unit'        => 'jaar',
                'is_price_on_quote'      => false,
                'verberg_in_configurator'=> true,
                'sort_order'  => 32,
            ],
            [
                'name'        => 'Onderhoudscontract access point — per stuk',
                'category'    => 'Service',
                'description' => 'Jaarlijks onderhoudscontract access points — toeslag per access point.',
                'unit_price'  => 20.00,
                'unit'        => 'jaar',
                'is_price_on_quote'      => false,
                'verberg_in_configurator'=> true,
                'sort_order'  => 33,
            ],
        ];

        foreach ($products as $data) {
            Product::firstOrCreate(
                ['name' => $data['name'], 'category' => $data['category']],
                array_merge($data, ['is_active' => true])
            );
        }

        // Ensure flags are set correctly on existing products (no-op on fresh installs)
        $flagUpdates = [
            ['name' => 'Optie A — Tower server (RAID 1)', 'is_hardware_basisoptie' => true, 'vereist_servicecontract' => true],
            ['name' => 'Optie B — HA Cluster (3 nodes)',  'is_hardware_basisoptie' => true, 'vereist_servicecontract' => true],
            ['name' => 'NUC node',                        'verberg_in_configurator' => true],
            ['name' => '2.5" SSD',                        'verberg_in_configurator' => true],
            ['name' => 'Firewall',                        'verberg_in_configurator' => true],
            ['name' => 'Switch standaard',                'verberg_in_configurator' => true],
            ['name' => 'PoE Switch 8-poorts',             'verberg_in_configurator' => true],
            ['name' => 'PoE Switch 16-poorts',            'verberg_in_configurator' => true],
            ['name' => 'NVR (Network Video Recorder)',    'verberg_in_configurator' => true],
            ['name' => 'Installatie halve dag (±4 uur)',  'verberg_in_configurator' => true],
            ['name' => 'Installatie hele dag (±8 uur)',   'verberg_in_configurator' => true],
            ['name' => 'UPS',                             'is_ups' => true],
        ];

        foreach ($flagUpdates as $update) {
            $name = $update['name'];
            unset($update['name']);
            Product::where('name', $name)->update($update);
        }
    }
}
