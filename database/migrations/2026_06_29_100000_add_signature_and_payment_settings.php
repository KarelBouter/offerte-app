<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\Setting;

return new class extends Migration
{
    public function up(): void
    {
        Setting::firstOrCreate(['key' => 'require_signature'],              ['value' => '1']);
        Setting::firstOrCreate(['key' => 'payment_onetime_mode'],           ['value' => '100_vooraf']);
        Setting::firstOrCreate(['key' => 'payment_service_days'],           ['value' => '14']);
        Setting::firstOrCreate(['key' => 'payment_service_yearly_advance'], ['value' => '1']);
    }

    public function down(): void
    {
        Setting::whereIn('key', [
            'require_signature',
            'payment_onetime_mode',
            'payment_service_days',
            'payment_service_yearly_advance',
        ])->delete();
    }
};
