<?php

namespace Database\Seeders;

use App\Models\AutoTaskTemplate;
use Illuminate\Database\Seeder;

class AutoTaskTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $templates = [
            [
                'name'                 => 'Factuur versturen',
                'trigger_status'       => 'ondertekend',
                'title_template'       => 'Verstuur factuur voor {{klant}}',
                'description_template' => 'Offerte {{offerte_nr}} is ondertekend. Factuur bedrag eenmalig: {{bedrag_eenmalig}}. Maak de factuur aan en stuur deze naar de klant.',
                'assign_to_user_id'    => null,
                'due_days'             => 3,
                'is_active'            => true,
                'sort_order'           => 1,
            ],
            [
                'name'                 => 'Installatieafspraak plannen',
                'trigger_status'       => 'ondertekend',
                'title_template'       => 'Plan installatieafspraak voor {{klant}}',
                'description_template' => 'Offerte {{offerte_nr}} is ondertekend. Neem contact op met {{klant}} om een installatieafspraak in te plannen.',
                'assign_to_user_id'    => null,
                'due_days'             => 5,
                'is_active'            => true,
                'sort_order'           => 2,
            ],
            [
                'name'                 => 'Verlopen offerte opvolgen',
                'trigger_status'       => 'verlopen',
                'title_template'       => 'Offerte {{offerte_nr}} verlopen – follow-up voor {{klant}}',
                'description_template' => 'Offerte {{offerte_nr}} voor {{klant}} is verlopen. Neem contact op om te bespreken of er interesse blijft en maak indien nodig een nieuwe offerte aan.',
                'assign_to_user_id'    => null,
                'due_days'             => 2,
                'is_active'            => true,
                'sort_order'           => 3,
            ],
        ];

        foreach ($templates as $template) {
            AutoTaskTemplate::firstOrCreate(
                ['name' => $template['name'], 'trigger_status' => $template['trigger_status']],
                $template
            );
        }
    }
}
