<?php

namespace App\Enums;

final class ItemGroup extends BaseEnum
{
    protected const DEFINITIONS = [
        'product'  => 'Proizvodi',
        'service'  => 'Usluge',
        'shipping' => 'Dostava',
        'discount' => 'Popusti',
        'fee'      => 'Trošak',
    ];
}