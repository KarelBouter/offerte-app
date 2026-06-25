<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            'company_name'           => 'Proud Innovations B.V.',
            'company_address'        => 'Zoetermeer',
            'company_kvk'            => '12345678',
            'company_representative' => 'Pascal Versluis — Directeur',
            'vat_percentage'         => '21',
            'quote_validity_days'    => '30',
        ];

        foreach ($defaults as $key => $value) {
            Setting::firstOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
