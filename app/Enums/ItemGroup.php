<?php

namespace App\Enums;

final class ItemGroup extends BaseEnum
{
    protected const DEFINITIONS = [
        'product'  => 'Proizvod',
        'service'  => 'Usluga',
        'shipping' => 'Dostava',
        'discount' => 'Popust',
        'fee'      => 'Trošak',
    ];
}