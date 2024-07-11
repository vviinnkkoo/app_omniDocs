<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Kpr extends Model
{
    use HasFactory;

    public function getAmountAttribute($value)
    {
        return str_replace('.', ',', $value);
    }

    // replace dot with comma for display
    public function setAmountAttribute($value)
    {
        // Handle cases where $value is null
        $this->attributes['amount'] = is_null($value) ? null : str_replace(',', '.', $value);

    }

    public function receiptx(): HasOne
    {
        return $this->hasOne(Receipt::class);
    }

    public function receipt()
    {
        return $this->hasMany(Receipt::class);
    }
}
