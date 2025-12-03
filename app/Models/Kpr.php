<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasSearch;

class Kpr extends Model
{
    use HasSearch;
    
    protected $fillable = [
        'payer',
        'amount',
        'origin',
        'date',
        'info',
        'payment_type_id'
    ];

    protected $casts = [
        'date' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------------------------
    */
    public function paymentType()
    {
        return $this->belongsTo(PaymentType::class);
    }

    public function receipt()
    {
        return $this->hasMany(Receipt::class);
    }

    public function kprItemList()
    {
        return $this->hasMany(KprItemList::class);
    }

    /*
    |--------------------------------------------------------------------------------------------
    | Mutators
    |--------------------------------------------------------------------------------------------
    */
    public function setAmountAttribute($value)
    {
        $this->attributes['amount'] = is_null($value) ? null : str_replace(',', '.', $value);
    }

    /*
    |--------------------------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------------------------
    */
    public function getFormatedAmountAttribute($value)
    {
        $value = $this->attributes['amount'];
        return is_null($value) ? null : str_replace('.', ',', $value);
    }

    public function getFormatedDateAttribute()
    {
        return $this->date ? $this->date->format('d.m.Y.') : null;
    }

    public function getInputFormatedDateAttribute()
    {
        return $this->date ? $this->date->format('Y-m-d') : null;
    }
}
