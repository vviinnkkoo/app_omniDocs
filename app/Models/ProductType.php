<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasSearch;

class ProductType extends Model
{
    use HasSearch;

    protected $fillable = [
        'name'
    ];
    
    /*
    |--------------------------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------------------------
    */
    public function product()
    {
        return $this->hasMany(Product::class)->orderBy('name', 'asc');
    }
}
