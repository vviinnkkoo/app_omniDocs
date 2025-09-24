<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kpr extends Model
{
    protected $fillable = [
        'payer',
        'amount',
        'origin',
        'date',
        'info',
        'kpr_payment_type_id'
    ];    

    /*
    |--------------------------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------------------------
    */
    public function receipt()
    {
        return $this->hasMany(Receipt::class);
    }

    public function paymentType()
    {
        return $this->belongsTo(KprPaymentType::class);
    }

    public function kprItemList()
    {
        return $this->hasMany(KprItemList::class);
    }

    /*
    |--------------------------------------------------------------------------------------------
    | SET accessors
    |--------------------------------------------------------------------------------------------
    */
    public function setAmountAttribute($value)
    {
        $this->attributes['amount'] = is_null($value) ? null : str_replace(',', '.', $value);
    }
}
