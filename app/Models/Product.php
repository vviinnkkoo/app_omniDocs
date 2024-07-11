<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // replace dot with comma for display
    public function getDefaultPriceAttribute($value)
    {
        return str_replace('.', ',', $value);
    }

    // replace dot with comma for display
    public function setDefaultPriceAttribute($value)
    {
        //$this->attributes['default_cost'] = str_replace(',', '.', $value);

        // Handle cases where $value is null
        $this->attributes['default_price'] = is_null($value) ? null : str_replace(',', '.', $value);

    }
}
