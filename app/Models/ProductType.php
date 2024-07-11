<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductType extends Model
{
    use HasFactory;

    // change default table name
    protected $table = 'product_type';

    public function orders()
    {
        return $this->hasMany('App\Product');
    }
}
