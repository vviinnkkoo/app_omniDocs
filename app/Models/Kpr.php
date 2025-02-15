<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Kpr extends Model
{
    use HasFactory;

    public function setAmountAttribute($value)
    {
        $this->attributes['amount'] = is_null($value) ? null : str_replace(',', '.', $value);
    }

    public function receipt()
    {
        return $this->hasMany(Receipt::class);
    }

    public function paymentType()
    {
        return $this->belongsTo(KprPaymentType::class, 'kpr_payment_type_id');
    }
}
