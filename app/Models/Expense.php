<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
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
}
