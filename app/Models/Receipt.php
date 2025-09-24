<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    protected $fillable = [
        'is_cancelled',
        'number',
        'order_id',
        'year'
    ];

    protected $casts = [
        'is_cancelled' => 'boolean'
    ];
    
    /*
    |--------------------------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------------------------
    */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function canceller()
    {
        return $this->hasOne(self::class);
    }

    public function kprItem()
    {
        return $this->hasOne(KprItemList::class);
    }

    /*
    |--------------------------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------------------------
    */
    //public function getIsCancelledAttribute(): bool
    //{
    //    return $this->canceller()->exists();
    //}

    //public function getIsCancellingAttribute(): bool
    //{
    //    return self::where('cancelled_receipt_id', $this->id)->exists();
    //}

    public function getHasPaymentAttribute(): bool
    {
        return $this->kprItem()->exists();
    }

    public function getPaymentIdAttribute(): ?int
    {
        return $this->kprItem()->value('kpr_id');
    }

    /*
    |--------------------------------------------------------------------------------------------
    | Mutators
    |--------------------------------------------------------------------------------------------
    */
    public function setPaidAmountAttribute($value)
    {
        $this->attributes['paid_amount'] = is_null($value) ? null : str_replace(',', '.', $value);
    }
}