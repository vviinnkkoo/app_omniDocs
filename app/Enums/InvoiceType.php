<?php

namespace App\Enums;

final class InvoiceType extends BaseEnum
{
    protected const DEFINITIONS = [
        'invoice'      => 'Račun',
        'credit'       => 'Odobrenje',
        'advance'      => 'Avansni račun',
        'cancellation' => 'Storno račun',
    ];
}