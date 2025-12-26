<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceItemList extends Model
{
    protected $fillable = [
        'invoice_id',
        'item_id',
        'name',
        'item_group_key',
        'description',
        'note',
        'price',
        'amount',
        'discount_amount',
        'discount_percentage',
        'vat_amount',
        'vat_percentage',
        'subtotal',
        'total',
    ];

    /*
    |--------------------------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------------------------
    */
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
