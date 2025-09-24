<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KprItemList extends Model
{
    protected $fillable = [
        'receipt_id',
        'kpr_id'
    ];

    /*
    |--------------------------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------------------------
    */
    public function receipt()
    {
        return $this->belongsTo(Receipt::class);
    }
}