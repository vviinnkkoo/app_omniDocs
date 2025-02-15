<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public function setDefaultPriceAttribute($value)
    {
        $this->attributes['default_price'] = is_null($value) ? null : str_replace(',', '.', $value);
    }

    public function productType()
    {
        return $this->belongsTo(ProductType::class, 'product_type_id');
    }
}
