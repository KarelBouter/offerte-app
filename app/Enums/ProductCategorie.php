<?php

namespace App\Enums;

enum ProductCategorie: string
{
    case Hardware    = 'Hardware';
    case Netwerk     = 'Netwerk';
    case Beveiliging = 'Beveiliging';
    case Installatie = 'Installatie';
    case Licenties   = 'Licenties';
    case Service     = 'Service';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
