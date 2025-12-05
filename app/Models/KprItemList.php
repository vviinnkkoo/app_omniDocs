<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KprItemList extends Model
{
    protected $fillable = [
        'invoice_id',
        'kpr_id'
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