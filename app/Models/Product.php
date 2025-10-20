<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasSearch;

class Product extends Model
{
    use HasSearch;
    
    protected $fillable = [
        'name',
        'product_type_id',
        'default_price'
    ];

    /*
    |--------------------------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------------------------
    */
    public function productType()
    {
        return $this->belongsTo(ProductType::class);
    }

    /*
    |--------------------------------------------------------------------------------------------
    | Mutators
    |--------------------------------------------------------------------------------------------
    */
    public function setDefaultPriceAttribute($value)
    {
        $this->attributes['default_price'] = is_null($value) ? null : str_replace(',', '.', $value);
    }
}
