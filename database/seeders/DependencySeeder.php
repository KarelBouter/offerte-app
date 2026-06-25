<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductDependency;
use Illuminate\Database\Seeder;

class DependencySeeder extends Seeder
{
    public function run(): void
    {
        // Resolve product IDs by name
        $optionA        = $this->find('Optie A — Tower server (RAID 1)');
        $optionB        = $this->find('Optie B — HA Cluster (3 nodes)');
        $firewall       = $this->find('Firewall');
        $switchStd      = $this->find('Switch standaard');
        $nuc            = $this->find('NUC node');
        $ssd            = $this->find('2.5" SSD');
        $ups            = $this->find('UPS');
        $camera         = $this->find('Beveiligingscamera (Unifi Protect)');
        $nvr            = $this->find('NVR (Network Video Recorder)');
        $poe8           = $this->find('PoE Switch 8-poorts');
        $poe16          = $this->find('PoE Switch 16-poorts');
        $svcStd         = $this->find('Servicecontract Standaard');
        $svcPremium     = $this->find('Servicecontract Premium');
        $installHalf    = $this->find('Installatie halve dag (±4 uur)');
        $installFull    = $this->find('Installatie hele dag (±8 uur)');

        $rules = [
            // Optie A sluit Optie B uit (en vice versa)
            [$optionA, 'EXCLUDES', $optionB, null, null, null, null, null],
            [$optionB, 'EXCLUDES', $optionA, null, null, null, null, null],

            // Optie A vereist Firewall en Switch standaard
            [$optionA, 'REQUIRED', $firewall,  null, null, 1, null, null],
            [$optionA, 'REQUIRED', $switchStd, null, null, 1, null, null],

            // Optie B vereist Firewall en Switch standaard
            [$optionB, 'REQUIRED', $firewall,  null, null, 1, null, null],
            [$optionB, 'REQUIRED', $switchStd, null, null, 1, null, null],

            // NUC node vereist 2× 2.5" SSD (RAID 1)
            [$nuc, 'REQUIRED', $ssd, null, null, 2, null, null],

            // UPS aanbevolen bij Optie B
            [$optionB, 'RECOMMENDED', $ups, null, null, null, null, null],

            // Beveiligingscamera vereist NVR
            [$camera, 'REQUIRED', $nvr, null, null, 1, null, null],

            // 1–8 camera's → PoE Switch 8-poorts
            [$camera, 'THRESHOLD_SWITCH', $poe8,  1, 8,  null, null, null],

            // 9–16 camera's → PoE Switch 16-poorts, vervangt 8-poorts
            [$camera, 'THRESHOLD_SWITCH', $poe16, 9, 16, null, null, $poe8],

            // Servicecontracten sluiten elkaar uit
            [$svcStd,     'EXCLUDES', $svcPremium, null, null, null, null, null],
            [$svcPremium, 'EXCLUDES', $svcStd,     null, null, null, null, null],

            // Optie A vereist basisinstallatie (halve dag)
            [$optionA, 'REQUIRED', $installHalf, null, null, 1, null, null],

            // Optie B krijgt hele dag installatie (vervangt halve dag)
            [$optionB, 'THRESHOLD_SWITCH', $installFull, 1, 999, null, null, $installHalf],
        ];

        foreach ($rules as [$product, $ruleType, $dependsOn, $trigMin, $trigMax, $resultQty, $formula, $replaces]) {
            ProductDependency::firstOrCreate(
                [
                    'product_id'           => $product->id,
                    'depends_on_product_id' => $dependsOn->id,
                    'rule_type'            => $ruleType,
                ],
                [
                    'trigger_quantity_min'        => $trigMin,
                    'trigger_quantity_max'        => $trigMax,
                    'resulting_quantity'          => $resultQty,
                    'resulting_quantity_formula'  => $formula,
                    'replaces_product_id'         => $replaces?->id,
                ]
            );
        }
    }

    private function find(string $name): Product
    {
        return Product::where('name', $name)->firstOrFail();
    }
}
