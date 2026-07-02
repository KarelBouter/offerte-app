<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductDependency;
use Illuminate\Database\Seeder;

class KassaContinuiteitsdienstSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedProducts();
        $this->seedDependencies();
    }

    // ── Products ──────────────────────────────────────────────────────────────

    private function seedProducts(): void
    {
        $products = [

            // ── Hardware ──────────────────────────────────────────────────────

            [
                'name'        => 'Optie A — Continuïteit Basis (Single NUC, RAID 1)',
                'category'    => 'Hardware',
                'description' => 'Compacte NUC-server met RAID 1 configuratie. Lokale dataopslag met '
                               . 'automatische failover op schijfniveau. Inclusief firewall, switch en UPS.',
                'unit_price'  => 4288.00,
                'unit'        => 'set',
                'is_price_on_quote'      => false,
                'is_hardware_basisoptie' => true,
                'vereist_servicecontract'=> true,
                'sort_order'  => 110,
            ],
            [
                'name'        => 'Optie B — High Availability Cluster (3 nodes)',
                'category'    => 'Hardware',
                'description' => 'HA-cluster van 3 NUC-nodes met gedeelde opslag en automatische '
                               . 'overname bij uitval van één node. Maximale uptime zonder handmatige interventie. '
                               . 'Inclusief firewall, switch en UPS.',
                'unit_price'  => 7295.00,
                'unit'        => 'set',
                'is_price_on_quote'      => false,
                'is_hardware_basisoptie' => true,
                'vereist_servicecontract'=> true,
                'sort_order'  => 120,
            ],
            // Inbegrepen componenten — verborgen in configurator, zichtbaar op werkbon
            [
                'name'        => 'Firewall (KCD)',
                'category'    => 'Hardware',
                'description' => 'Firewall — inbegrepen bij Optie A en B (KCD).',
                'unit_price'  => 0.00,
                'unit'        => 'stuk',
                'is_price_on_quote'      => true,
                'verberg_in_configurator'=> true,
                'sort_order'  => 130,
            ],
            [
                'name'        => 'Netwerkswitch (KCD, standaard)',
                'category'    => 'Hardware',
                'description' => 'Standaard netwerkswitch — inbegrepen bij Optie A en B (KCD).',
                'unit_price'  => 0.00,
                'unit'        => 'stuk',
                'is_price_on_quote'      => true,
                'verberg_in_configurator'=> true,
                'sort_order'  => 140,
            ],
            [
                'name'        => 'UPS (KCD)',
                'category'    => 'Hardware',
                'description' => 'Uninterruptible Power Supply — inbegrepen bij Optie A en B (KCD).',
                'unit_price'  => 0.00,
                'unit'        => 'stuk',
                'is_price_on_quote'      => true,
                'verberg_in_configurator'=> true,
                'is_ups'                 => true,
                'sort_order'  => 150,
            ],

            // ── Installatie ───────────────────────────────────────────────────

            [
                'name'        => 'Installatie eerste dag (KCD)',
                'category'    => 'Installatie',
                'description' => 'On-site installatie en configuratie, eerste dag (circa 8 uur). '
                               . 'Inbegrepen bij Optie A en B.',
                'unit_price'  => 800.00,
                'unit'        => 'dag',
                'is_price_on_quote'      => false,
                'verberg_in_configurator'=> true,
                'sort_order'  => 110,
            ],
            [
                'name'        => 'Installatie extra dag (KCD)',
                'category'    => 'Installatie',
                'description' => 'Extra dag on-site voor aanvullende installatie of configuratie (KCD).',
                'unit_price'  => 450.00,
                'unit'        => 'dag',
                'is_price_on_quote'      => false,
                'sort_order'  => 120,
            ],
            [
                'name'        => 'Netwerkkabel aanleggen (KCD)',
                'category'    => 'Installatie',
                'description' => 'Aanleg van netwerkkabels, inclusief kabelgoten en wanddoorvoeren. '
                               . 'Prijs: starttarief per run + toeslag per strekkende meter.',
                'unit_price'  => 7.50,
                'price_per_meter' => 1.00,
                'unit'        => 'stuk',
                'is_price_on_quote'      => false,
                'sort_order'  => 130,
            ],

            // ── Licenties ─────────────────────────────────────────────────────

            [
                'name'        => 'Windows 11 IoT licentie',
                'category'    => 'Licenties',
                'description' => 'Windows 11 IoT Enterprise licentie — eenmalig per geïnstalleerd apparaat.',
                'unit_price'  => 120.00,
                'unit'        => 'stuk',
                'is_price_on_quote'      => false,
                'sort_order'  => 10,
            ],
            [
                'name'        => 'Microsoft SQL Server licentie',
                'category'    => 'Licenties',
                'description' => 'Microsoft SQL Server licentie — eenmalig per installatie.',
                'unit_price'  => 998.00,
                'unit'        => 'stuk',
                'is_price_on_quote'      => false,
                'sort_order'  => 20,
            ],
            [
                'name'        => 'Microsoft SQL Device CAL',
                'category'    => 'Licenties',
                'description' => 'Client Access Licence per kassa-apparaat — aantal in te vullen door verkoper.',
                'unit_price'  => 210.00,
                'unit'        => 'stuk',
                'is_price_on_quote'      => false,
                'sort_order'  => 30,
            ],

            // ── Service ───────────────────────────────────────────────────────

            [
                'name'        => 'Support Basis (KCD)',
                'category'    => 'Service',
                'description' => 'Jaarlijks supportcontract Basis — monitoring, helpdeskondersteuning en '
                               . 'remote updates tijdens kantoortijden.',
                'unit_price'  => 8499.00,
                'unit'        => 'jaar',
                'is_price_on_quote'      => false,
                'sort_order'  => 110,
            ],
            [
                'name'        => 'Support Uitgebreid (KCD)',
                'category'    => 'Service',
                'description' => 'Jaarlijks supportcontract Uitgebreid — uitgebreide SLA, proactief beheer '
                               . 'en prioriteit helpdesk.',
                'unit_price'  => 12349.00,
                'unit'        => 'jaar',
                'is_price_on_quote'      => false,
                'sort_order'  => 120,
            ],
            [
                'name'        => 'Support Premium (KCD)',
                'category'    => 'Service',
                'description' => 'Jaarlijks supportcontract Premium — maximale SLA, dedicated contactpersoon, '
                               . '24/7 bereikbaarheid en on-site response.',
                'unit_price'  => 16587.00,
                'unit'        => 'jaar',
                'is_price_on_quote'      => false,
                'sort_order'  => 130,
            ],
        ];

        foreach ($products as $data) {
            Product::firstOrCreate(
                ['name' => $data['name'], 'category' => $data['category']],
                array_merge(['is_active' => true], $data)
            );
        }

        // Placeholder PoE switches voor Camera's & Netwerk module (nog niet actief)
        $placeholders = [
            [
                'name'        => 'PoE Switch 8-poorts (KCD)',
                'category'    => 'Hardware',
                'description' => 'Power over Ethernet switch 8 poorten — placeholder voor toekomstige '
                               . 'Camera\'s & Netwerk module (KCD).',
                'unit_price'  => 0.00,
                'unit'        => 'stuk',
                'is_price_on_quote'      => true,
                'verberg_in_configurator'=> true,
                'sort_order'  => 160,
            ],
            [
                'name'        => 'PoE Switch 16-poorts (KCD)',
                'category'    => 'Hardware',
                'description' => 'Power over Ethernet switch 16 poorten — placeholder voor toekomstige '
                               . 'Camera\'s & Netwerk module (KCD).',
                'unit_price'  => 0.00,
                'unit'        => 'stuk',
                'is_price_on_quote'      => true,
                'verberg_in_configurator'=> true,
                'sort_order'  => 170,
            ],
        ];

        foreach ($placeholders as $data) {
            Product::firstOrCreate(
                ['name' => $data['name'], 'category' => $data['category']],
                array_merge(['is_active' => false], $data)   // inactief tot Camera-module live gaat
            );
        }
    }

    // ── Dependencies ──────────────────────────────────────────────────────────

    private function seedDependencies(): void
    {
        $optieA      = $this->find('Optie A — Continuïteit Basis (Single NUC, RAID 1)');
        $optieB      = $this->find('Optie B — High Availability Cluster (3 nodes)');
        $firewall    = $this->find('Firewall (KCD)');
        $switchStd   = $this->find('Netwerkswitch (KCD, standaard)');
        $ups         = $this->find('UPS (KCD)');
        $installD1   = $this->find('Installatie eerste dag (KCD)');
        $supBasis    = $this->find('Support Basis (KCD)');
        $supUitgbr   = $this->find('Support Uitgebreid (KCD)');
        $supPremium  = $this->find('Support Premium (KCD)');

        // TODO: Camera's & Netwerk module (activeren zodra producten en prijzen bekend zijn)
        // $poe8     = $this->find('PoE Switch 8-poorts (KCD)');
        // $poe16    = $this->find('PoE Switch 16-poorts (KCD)');
        // $camera   = $this->find('...');   // nieuw camera-product KCD
        // [$camera, 'THRESHOLD_SWITCH', $poe8,  1, 8,  null, null, null]
        // [$camera, 'THRESHOLD_SWITCH', $poe16, 9, 16, null, null, $poe8]

        $rules = [
            // Opties sluiten elkaar uit
            [$optieA, 'EXCLUDES', $optieB, null, null, null, null, null],
            [$optieB, 'EXCLUDES', $optieA, null, null, null, null, null],

            // Optie A vereist inbegrepen componenten (verschijnen op werkbon, niet op offerte)
            [$optieA, 'REQUIRED', $firewall,  null, null, 1, null, null],
            [$optieA, 'REQUIRED', $switchStd, null, null, 1, null, null],
            [$optieA, 'REQUIRED', $ups,       null, null, 1, null, null],

            // Optie B vereist dezelfde componenten
            [$optieB, 'REQUIRED', $firewall,  null, null, 1, null, null],
            [$optieB, 'REQUIRED', $switchStd, null, null, 1, null, null],
            [$optieB, 'REQUIRED', $ups,       null, null, 1, null, null],

            // Beide opties vereisen basisinstallatie eerste dag
            [$optieA, 'REQUIRED', $installD1, null, null, 1, null, null],
            [$optieB, 'REQUIRED', $installD1, null, null, 1, null, null],

            // Support-niveaus sluiten elkaar onderling uit
            [$supBasis,   'EXCLUDES', $supUitgbr,  null, null, null, null, null],
            [$supBasis,   'EXCLUDES', $supPremium, null, null, null, null, null],
            [$supUitgbr,  'EXCLUDES', $supBasis,   null, null, null, null, null],
            [$supUitgbr,  'EXCLUDES', $supPremium, null, null, null, null, null],
            [$supPremium, 'EXCLUDES', $supBasis,   null, null, null, null, null],
            [$supPremium, 'EXCLUDES', $supUitgbr,  null, null, null, null, null],
        ];

        foreach ($rules as [$product, $ruleType, $dependsOn, $trigMin, $trigMax, $resultQty, $formula, $replaces]) {
            ProductDependency::firstOrCreate(
                [
                    'product_id'            => $product->id,
                    'depends_on_product_id' => $dependsOn->id,
                    'rule_type'             => $ruleType,
                ],
                [
                    'trigger_quantity_min'       => $trigMin,
                    'trigger_quantity_max'       => $trigMax,
                    'resulting_quantity'         => $resultQty,
                    'resulting_quantity_formula' => $formula,
                    'replaces_product_id'        => $replaces?->id,
                ]
            );
        }
    }

    private function find(string $name): Product
    {
        return Product::where('name', $name)->firstOrFail();
    }
}
