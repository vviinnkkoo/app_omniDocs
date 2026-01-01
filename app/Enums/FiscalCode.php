<?php

namespace App\Enums;

final class FiscalCode extends BaseEnum
{
    protected const DEFINITIONS = [
        'G' => 'Gotovina',
        'K' => 'Kartice',
        'T' => 'Transakcijski račun',
        'O' => 'Ostalo',
    ];
}