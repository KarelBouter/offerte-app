<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Config;

class MailSettingsService
{
    public static function applyFromDatabase(): void
    {
        $mailer     = Setting::get('mail_mailer', 'smtp');
        $host       = Setting::get('mail_host', '');
        $port       = (int) Setting::get('mail_port', '587');
        $encryption = Setting::get('mail_encryption', 'tls');
        $username   = Setting::get('mail_username', '');
        $password   = Setting::get('mail_password', '');
        $fromAddr   = Setting::get('mail_from_address', 'noreply@proudinnovations.nl');
        $fromName   = Setting::get('mail_from_name', 'Proud Innovations B.V.');

        Config::set('mail.default', $mailer);
        Config::set('mail.mailers.smtp.host', $host);
        Config::set('mail.mailers.smtp.port', $port);
        Config::set('mail.mailers.smtp.encryption', $encryption ?: null);
        Config::set('mail.mailers.smtp.username', $username ?: null);
        Config::set('mail.mailers.smtp.password', $password ?: null);
        Config::set('mail.from.address', $fromAddr);
        Config::set('mail.from.name', $fromName);
    }
}
