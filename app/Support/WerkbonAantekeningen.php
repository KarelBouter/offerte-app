<?php

namespace App\Support;

class WerkbonAantekeningen
{
    public const OPTIES = [
        'kantoor_regelt'    => 'Regelt kantoor',
        'bij_ingebruikname' => 'Bij ingebruikname',
        'nvt'               => 'Niet van toepassing voor deze installatie',
    ];

    public const KORT = [
        'kantoor_regelt'    => 'KA',
        'bij_ingebruikname' => 'IB',
        'nvt'               => 'NVT',
    ];

    public const ZICHTBAARHEID = [
        'automatisch' => 'Automatisch (standaard)',
        'altijd'      => 'Altijd tonen',
        'verbergen'   => 'Standaard verbergen',
    ];
}
