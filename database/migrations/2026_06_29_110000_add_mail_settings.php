<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\Setting;

return new class extends Migration
{
    public function up(): void
    {
        Setting::firstOrCreate(['key' => 'mail_mailer'],          ['value' => 'smtp']);
        Setting::firstOrCreate(['key' => 'mail_host'],            ['value' => '']);
        Setting::firstOrCreate(['key' => 'mail_port'],            ['value' => '587']);
        Setting::firstOrCreate(['key' => 'mail_encryption'],      ['value' => 'tls']);
        Setting::firstOrCreate(['key' => 'mail_username'],        ['value' => '']);
        Setting::firstOrCreate(['key' => 'mail_password'],        ['value' => '']);
        Setting::firstOrCreate(['key' => 'mail_from_address'],    ['value' => 'noreply@proudinnovations.nl']);
        Setting::firstOrCreate(['key' => 'mail_from_name'],       ['value' => 'Proud Innovations B.V.']);
        Setting::firstOrCreate(['key' => 'mail_subject_quote'],   ['value' => 'Offerte van Proud Innovations B.V. — {quote_number}']);
        Setting::firstOrCreate(['key' => 'mail_subject_welcome'], ['value' => 'Welkom bij de Proud Innovations offerte-applicatie']);
    }

    public function down(): void
    {
        \App\Models\Setting::whereIn('key', [
            'mail_mailer', 'mail_host', 'mail_port', 'mail_encryption',
            'mail_username', 'mail_password', 'mail_from_address', 'mail_from_name',
            'mail_subject_quote', 'mail_subject_welcome',
        ])->delete();
    }
};
