<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductType extends Model
{
    protected $table = 'product_type';

    public function orders()
    {
        return $this->hasMany('App\Product');
    }

    public function product()
    {
        return $this->hasMany(Product::class)->orderBy('name', 'asc');
    }
}
